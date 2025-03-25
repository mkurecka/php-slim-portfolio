<?php

namespace App\Application\Actions\Admin;

use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class SiteContentAction
{
    private PhpRenderer $renderer;
    private SiteContentService $contentService;

    public function __construct(PhpRenderer $adminRenderer, SiteContentService $contentService)
    {
        $this->renderer = $adminRenderer;
        $this->contentService = $contentService;
    }

    public function listSections(Request $request, Response $response): Response
    {
        $content = $this->contentService->getAllContent();
        
        return $this->renderer->render($response, 'admin/content/index.php', [
            'title' => 'Site Content | Admin',
            'sections' => array_keys($content)
        ]);
    }
    
    public function editSection(Request $request, Response $response, array $args): Response
    {
        $section = $args['section'];
        $content = $this->contentService->getContent($section);
        
        if (empty($content) && $section !== 'profile' && $section !== 'one-column') {
            return $response->withStatus(404);
        }
        
        // Special handling for profile section
        if ($section === 'profile') {
            return $this->renderer->render($response, 'admin/content/profile.php', [
                'title' => 'Edit Profile Content | Admin',
                'content' => $content
            ]);
        }
        
        // Special handling for one-column template section
        if ($section === 'one-column') {
            return $this->renderer->render($response, 'admin/content/one-column.php', [
                'title' => 'Edit One-Column Template | Admin',
                'content' => $content
            ]);
        }
        
        return $this->renderer->render($response, 'admin/content/edit.php', [
            'title' => 'Edit ' . ucfirst($section) . ' Content | Admin',
            'section' => $section,
            'content' => $content
        ]);
    }
    
    public function updateSection(Request $request, Response $response, array $args): Response
    {
        $section = $args['section'];
        $data = $request->getParsedBody();
        
        // Special handling for profile section
        if ($section === 'profile' && isset($data['content']) && is_array($data['content'])) {
            $content = $data['content'];
            $success = $this->contentService->updateSection($section, $content);
            
            if (!$success) {
                return $this->renderer->render($response->withStatus(500), 'admin/content/profile.php', [
                    'title' => 'Edit Profile Content | Admin',
                    'content' => $content,
                    'error' => 'Failed to save profile content changes.'
                ]);
            }
            
            return $this->renderer->render($response, 'admin/content/profile.php', [
                'title' => 'Edit Profile Content | Admin',
                'content' => $content,
                'success' => true
            ]);
        }
        
        // Special handling for one-column template section
        if ($section === 'one-column' && isset($data['content']) && is_array($data['content'])) {
            $content = $data['content'];
            $success = $this->contentService->updateSection($section, $content);
            
            if (!$success) {
                return $this->renderer->render($response->withStatus(500), 'admin/content/one-column.php', [
                    'title' => 'Edit One-Column Template | Admin',
                    'content' => $content,
                    'error' => 'Failed to save template changes.'
                ]);
            }
            
            return $this->renderer->render($response, 'admin/content/one-column.php', [
                'title' => 'Edit One-Column Template | Admin',
                'content' => $content,
                'success' => true
            ]);
        }
        
        // Standard JSON content handling for other sections
        if (isset($data['content']) && is_string($data['content'])) {
            $content = json_decode($data['content'], true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $data = [
                    'error' => 'Invalid JSON format: ' . json_last_error_msg()
                ];
                return $this->renderer->render($response->withStatus(400), 'admin/content/edit.php', [
                    'title' => 'Edit ' . ucfirst($section) . ' Content | Admin',
                    'section' => $section,
                    'content' => $this->contentService->getContent($section),
                    'error' => 'Invalid JSON format: ' . json_last_error_msg()
                ]);
            }
            
            $success = $this->contentService->updateSection($section, $content);
            
            if (!$success) {
                return $this->renderer->render($response->withStatus(500), 'admin/content/edit.php', [
                    'title' => 'Edit ' . ucfirst($section) . ' Content | Admin',
                    'section' => $section,
                    'content' => $content,
                    'error' => 'Failed to save content changes.'
                ]);
            }
            
            return $this->renderer->render($response, 'admin/content/edit.php', [
                'title' => 'Edit ' . ucfirst($section) . ' Content | Admin',
                'section' => $section,
                'content' => $content,
                'success' => 'Content updated successfully.'
            ]);
        }
        
        return $response->withStatus(400);
    }
}
