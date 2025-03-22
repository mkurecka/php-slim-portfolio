<?php

namespace App\Infrastructure\Webhook;

use App\Domain\Blog\BlogPost;
use App\Domain\Blog\BlogRepository;
use App\Domain\Contact\ContactSubmission;
use App\Infrastructure\Content\SiteContentService;

class WebhookService
{
    private SiteContentService $contentService;
    private ?BlogRepository $blogRepository;

    public function __construct(
        SiteContentService $contentService,
        ?BlogRepository $blogRepository = null
    ) {
        $this->contentService = $contentService;
        $this->blogRepository = $blogRepository;
    }

    /**
     * Send a contact form submission to the configured webhook URL
     */
    public function sendContactSubmission(ContactSubmission $submission): bool
    {
        $contactSettings = $this->contentService->getContent('contact');
        
        // Check if webhook is enabled and URL is set
        if (
            !isset($contactSettings['webhook']['enabled']) || 
            !$contactSettings['webhook']['enabled'] || 
            empty($contactSettings['webhook']['url'])
        ) {
            return false;
        }
        
        $webhookUrl = $contactSettings['webhook']['url'];
        $webhookSecret = $contactSettings['webhook']['secret'] ?? '';
        
        // Prepare the payload
        $payload = json_encode([
            'timestamp' => time(),
            'data' => $submission->toArray(),
            'signature' => $this->generateSignature($submission, $webhookSecret)
        ]);
        
        // Initialize cURL
        $ch = curl_init($webhookUrl);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'X-Portfolio-Webhook: 1'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 seconds timeout
        
        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Close cURL
        curl_close($ch);
        
        // Log the webhook attempt
        $this->logWebhookAttempt('contact', $submission->getId(), $webhookUrl, $httpCode, $response);
        
        // Success if HTTP code is 2xx
        return $httpCode >= 200 && $httpCode < 300;
    }
    
    /**
     * Process an incoming blog post webhook
     * 
     * @param array $data The blog post data
     * @param string $apiKey The API key from the request
     * @return array Response with status and message
     */
    public function processBlogPostWebhook(array $data, string $apiKey): array
    {
        // Get blog webhook settings
        $blogSettings = $this->contentService->getContent('blog_webhook') ?? [
            'enabled' => false,
            'api_key' => '',
        ];
        
        // Check if webhook is enabled
        if (!isset($blogSettings['enabled']) || !$blogSettings['enabled']) {
            $this->logWebhookAttempt('blog', 'N/A', 'N/A', 403, 'Webhook is disabled');
            return [
                'success' => false,
                'message' => 'Blog webhook is disabled',
                'status' => 403
            ];
        }
        
        // Validate API key
        if (empty($blogSettings['api_key']) || $apiKey !== $blogSettings['api_key']) {
            $this->logWebhookAttempt('blog', 'N/A', 'N/A', 401, 'Invalid API key');
            return [
                'success' => false,
                'message' => 'Invalid API key',
                'status' => 401
            ];
        }
        
        // Validate required fields
        if (empty($data['title']) || empty($data['content'])) {
            $this->logWebhookAttempt('blog', 'N/A', 'N/A', 400, 'Missing required fields');
            return [
                'success' => false,
                'message' => 'Missing required fields: title and content are required',
                'status' => 400
            ];
        }
        
        // Create blog post
        try {
            if (!$this->blogRepository) {
                $this->blogRepository = new BlogRepository();
            }
            
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['title']);
            }
            
            // Set date to today if not provided
            if (empty($data['date'])) {
                $data['date'] = date('Y-m-d');
            }
            
            // Set default values for optional fields
            $data['excerpt'] = $data['excerpt'] ?? $this->generateExcerpt($data['content']);
            $data['tags'] = $data['tags'] ?? [];
            $data['youtube_url'] = $data['youtube_url'] ?? null;
            
            // Create blog post object (ID will be assigned by the repository)
            $blogPost = BlogPost::fromArray([
                'id' => 0, // Will be assigned by repository
                'title' => $data['title'],
                'slug' => $data['slug'],
                'date' => $data['date'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'tags' => $data['tags'],
                'youtube_url' => $data['youtube_url']
            ]);
            
            // Save blog post
            $this->blogRepository->save($blogPost);
            
            $this->logWebhookAttempt('blog', $blogPost->getSlug(), 'N/A', 201, 'Blog post created successfully');
            
            return [
                'success' => true,
                'message' => 'Blog post created successfully',
                'status' => 201,
                'post' => [
                    'id' => $blogPost->getId(),
                    'slug' => $blogPost->getSlug(),
                    'title' => $blogPost->getTitle()
                ]
            ];
        } catch (\Exception $e) {
            $this->logWebhookAttempt('blog', 'N/A', 'N/A', 500, 'Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error creating blog post: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }
    
    /**
     * Generate a slug from a title
     */
    private function generateSlug(string $title): string
    {
        // Convert to lowercase
        $slug = strtolower($title);
        
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Remove leading/trailing hyphens
        $slug = trim($slug, '-');
        
        return $slug;
    }
    
    /**
     * Generate an excerpt from content
     */
    private function generateExcerpt(string $content, int $length = 150): string
    {
        // Strip markdown formatting
        $text = preg_replace('/[#*_\[\]\(\)]+/', '', $content);
        
        // Truncate to specified length
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length) . '...';
        }
        
        return $text;
    }
    
    /**
     * Generate a signature for the webhook payload
     */
    private function generateSignature(ContactSubmission $submission, string $secret): string
    {
        if (empty($secret)) {
            return '';
        }
        
        // Use the getter methods to ensure we have valid data
        $stringToSign = $submission->getId() . $submission->getEmail() . $submission->getDate();
        
        return hash_hmac('sha256', $stringToSign, $secret);
    }
    
    /**
     * Log webhook attempt
     */
    private function logWebhookAttempt(string $type, string $id, string $url, int $statusCode, $response): void
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => $type,
            'id' => $id,
            'url' => $url,
            'status_code' => $statusCode,
            'response' => $response ?: 'No response'
        ];
        
        $logFile = __DIR__ . '/../../../data/webhook_logs.json';
        
        // Create or read existing log file
        if (!file_exists($logFile)) {
            $logs = [];
        } else {
            $logs = json_decode(file_get_contents($logFile), true) ?: [];
        }
        
        // Add new log entry
        $logs[] = $logData;
        
        // Keep only the last 100 log entries
        if (count($logs) > 100) {
            $logs = array_slice($logs, -100);
        }
        
        // Save log file
        file_put_contents(
            $logFile, 
            json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
