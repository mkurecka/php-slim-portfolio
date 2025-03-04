<?php

namespace App\Application\Actions;

use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

abstract class BaseAction
{
    protected PhpRenderer $renderer;
    protected SiteContentService $contentService;

    public function __construct(PhpRenderer $renderer, SiteContentService $contentService)
    {
        $this->renderer = $renderer;
        $this->contentService = $contentService;
    }

    protected function renderWithContent(Response $response, string $template, array $data = []): Response
    {
        // Add site content to the template data
        $data['site_content'] = $this->contentService->getAllContent();
        
        return $this->renderer->render($response, $template, $data);
    }
}