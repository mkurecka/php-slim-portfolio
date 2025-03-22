<?php

namespace App\Application\Actions\Admin;

use App\Domain\Blog\BlogRepository;
use App\Infrastructure\Content\SiteContentService;
use App\Infrastructure\Webhook\WebhookService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class WebhookController
{
    private PhpRenderer $renderer;
    private SiteContentService $contentService;
    private WebhookService $webhookService;
    private BlogRepository $blogRepository;

    public function __construct(
        PhpRenderer $adminRenderer, 
        SiteContentService $contentService,
        WebhookService $webhookService,
        BlogRepository $blogRepository
    ) {
        $this->renderer = $adminRenderer;
        $this->contentService = $contentService;
        $this->webhookService = $webhookService;
        $this->blogRepository = $blogRepository;
    }

    public function settings(Request $request, Response $response): Response
    {
        $contactSettings = $this->contentService->getContent('contact');
        $blogWebhookSettings = $this->contentService->getContent('blog_webhook') ?? [
            'enabled' => false,
            'api_key' => ''
        ];
        $webhookLogs = $this->getWebhookLogs();
        
        return $this->renderer->render($response, 'admin/webhook/settings.php', [
            'title' => 'Webhook Settings | Admin',
            'webhook' => $contactSettings['webhook'] ?? [
                'enabled' => false,
                'url' => '',
                'secret' => ''
            ],
            'blog_webhook' => $blogWebhookSettings,
            'logs' => $webhookLogs
        ]);
    }
    
    public function updateSettings(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $contactSettings = $this->contentService->getContent('contact');
        $blogWebhookSettings = $this->contentService->getContent('blog_webhook') ?? [];
        
        // Update contact webhook settings
        $contactSettings['webhook'] = [
            'enabled' => isset($data['contact_enabled']) && $data['contact_enabled'] === 'on',
            'url' => $data['contact_url'] ?? '',
            'secret' => $data['contact_secret'] ?? ''
        ];
        
        // Update blog webhook settings
        $blogWebhookSettings = [
            'enabled' => isset($data['blog_enabled']) && $data['blog_enabled'] === 'on',
            'api_key' => $data['blog_api_key'] ?? ''
        ];
        
        // Generate new API key if requested
        if (isset($data['generate_api_key']) && $data['generate_api_key'] === 'on') {
            $blogWebhookSettings['api_key'] = bin2hex(random_bytes(16)); // 32 character hex string
        }
        
        // Save settings
        $contactSuccess = $this->contentService->updateSection('contact', $contactSettings);
        $blogSuccess = $this->contentService->updateSection('blog_webhook', $blogWebhookSettings);
        
        $success = $contactSuccess && $blogSuccess;
        
        return $this->renderer->render($response, 'admin/webhook/settings.php', [
            'title' => 'Webhook Settings | Admin',
            'webhook' => $contactSettings['webhook'],
            'blog_webhook' => $blogWebhookSettings,
            'logs' => $this->getWebhookLogs(),
            'success' => $success ? 'Webhook settings updated successfully!' : null,
            'error' => !$success ? 'Failed to update webhook settings.' : null
        ]);
    }
    
    public function clearLogs(Request $request, Response $response): Response
    {
        $logFile = __DIR__ . '/../../../../data/webhook_logs.json';
        
        if (file_exists($logFile)) {
            file_put_contents($logFile, json_encode([]));
        }
        
        return $response->withHeader('Location', '/admin/webhook?cleared=1')->withStatus(302);
    }
    
    /**
     * Handle incoming blog post webhook
     */
    public function handleBlogWebhook(Request $request, Response $response): Response
    {
        // Get API key from header or query parameter
        $apiKey = $this->extractApiKey($request);
        
        // Get request body
        $body = $request->getParsedBody();
        if (!$body && $request->getBody()) {
            $body = json_decode($request->getBody()->getContents(), true);
        }
        
        if (!$body || !is_array($body)) {
            $result = [
                'success' => false,
                'message' => 'Invalid request body',
                'status' => 400
            ];
            return $this->jsonResponse($response->withStatus(400), $result);
        }
        
        // Process webhook
        $result = $this->webhookService->processBlogPostWebhook($body, $apiKey);
        
        // Return response
        return $this->jsonResponse(
            $response->withStatus($result['status']), 
            $result
        );
    }
    
    /**
     * Extract API key from request
     */
    private function extractApiKey(Request $request): string
    {
        // Try to get from Authorization header
        $authHeader = $request->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        
        // Try to get from X-API-Key header
        $apiKeyHeader = $request->getHeaderLine('X-API-Key');
        if (!empty($apiKeyHeader)) {
            return $apiKeyHeader;
        }
        
        // Try to get from query parameter
        $params = $request->getQueryParams();
        if (isset($params['api_key'])) {
            return $params['api_key'];
        }
        
        return '';
    }
    
    /**
     * Return JSON response
     */
    private function jsonResponse(Response $response, array $data): Response
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    private function getWebhookLogs(): array
    {
        $logFile = __DIR__ . '/../../../../data/webhook_logs.json';
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $logs = json_decode(file_get_contents($logFile), true);
        
        if (!is_array($logs)) {
            return [];
        }
        
        // Reverse array so newest is first
        return array_reverse($logs);
    }
}
