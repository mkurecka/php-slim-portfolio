<?php

namespace App\Application\Actions\Admin;

use App\Application\Settings\SettingsInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class SettingsController
{
    private PhpRenderer $renderer;
    private SettingsInterface $settings;

    public function __construct(PhpRenderer $adminRenderer, SettingsInterface $settings)
    {
        $this->renderer = $adminRenderer;
        $this->settings = $settings;
    }

    public function index(Request $request, Response $response): Response
    {
        $templateSettings = $this->settings->get('templates');
        
        return $this->renderer->render($response, 'admin/settings/index.php', [
            'title' => 'Template Settings | Admin',
            'templates' => $templateSettings
        ]);
    }
    
    public function update(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $templateSettings = $this->settings->get('templates');
        
        // Update template settings
        if (isset($data['templates']) && is_array($data['templates'])) {
            $templateSettings = array_merge($templateSettings, $data['templates']);
            
            // Save settings to file
            $settingsFile = __DIR__ . '/../../../../app/settings.php';
            $settingsContent = file_get_contents($settingsFile);
            
            // Replace the templates section in the settings file
            $pattern = "/('templates'\\s*=>\\s*\\[)(.*?)(\\],)/s";
            $replacement = "$1\n                    'blog' => '" . $templateSettings['blog'] . "', // Options: 'default', 'simple'\n                    'site' => '" . $templateSettings['site'] . "', // Options: 'default', 'one-column'\n                $3";
            $settingsContent = preg_replace($pattern, $replacement, $settingsContent);
            
            file_put_contents($settingsFile, $settingsContent);
        }
        
        return $this->renderer->render($response, 'admin/settings/index.php', [
            'title' => 'Template Settings | Admin',
            'templates' => $templateSettings,
            'success' => 'Template settings updated successfully.'
        ]);
    }
}
