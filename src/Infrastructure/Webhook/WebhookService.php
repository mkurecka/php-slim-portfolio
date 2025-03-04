<?php

namespace App\Infrastructure\Webhook;

use App\Domain\Contact\ContactSubmission;
use App\Infrastructure\Content\SiteContentService;

class WebhookService
{
    private SiteContentService $contentService;

    public function __construct(SiteContentService $contentService)
    {
        $this->contentService = $contentService;
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
        $this->logWebhookAttempt($submission->getId(), $webhookUrl, $httpCode, $response);
        
        // Success if HTTP code is 2xx
        return $httpCode >= 200 && $httpCode < 300;
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
    private function logWebhookAttempt(string $submissionId, string $webhookUrl, int $httpCode, $response): void
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'submission_id' => $submissionId,
            'webhook_url' => $webhookUrl,
            'http_code' => $httpCode,
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