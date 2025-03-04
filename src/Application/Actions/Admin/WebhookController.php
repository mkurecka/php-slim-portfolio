<?php

namespace App\Application\Actions\Admin;

use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class WebhookController
{
    private PhpRenderer $renderer;
    private SiteContentService $contentService;

    public function __construct(PhpRenderer $adminRenderer, SiteContentService $contentService)
    {
        $this->renderer = $adminRenderer;
        $this->contentService = $contentService;
    }

    public function settings(Request $request, Response $response): Response
    {
        $contactSettings = $this->contentService->getContent('contact');
        $webhookLogs = $this->getWebhookLogs();
        
        return $this->renderer->render($response, 'admin/webhook/settings.php', [
            'title' => 'Webhook Settings | Admin',
            'webhook' => $contactSettings['webhook'] ?? [
                'enabled' => false,
                'url' => '',
                'secret' => ''
            ],
            'logs' => $webhookLogs
        ]);
    }
    
    public function updateSettings(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $contactSettings = $this->contentService->getContent('contact');
        
        // Update webhook settings
        $contactSettings['webhook'] = [
            'enabled' => isset($data['enabled']) && $data['enabled'] === 'on',
            'url' => $data['url'] ?? '',
            'secret' => $data['secret'] ?? ''
        ];
        
        // Save settings
        $success = $this->contentService->updateSection('contact', $contactSettings);
        
        return $this->renderer->render($response, 'admin/webhook/settings.php', [
            'title' => 'Webhook Settings | Admin',
            'webhook' => $contactSettings['webhook'],
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