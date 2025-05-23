<?php

namespace App\Application\Actions;

use App\Infrastructure\Content\SiteContentService;
use App\Infrastructure\Locale\LocaleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

    protected function renderWithContent(Response $response, string $template, array $data = [], Request $request = null): Response
    {
        // Add site content to the template data
        $data['site_content'] = $this->contentService->getAllContent();
        
        // Add locale information if request is available
        if ($request) {
            $data['locale'] = $request->getAttribute('locale', 'en');
            $data['localeService'] = $request->getAttribute('localeService');
        }
        
        return $this->renderer->render($response, $template, $data);
    }

    protected function getLocaleFromRequest(Request $request): string
    {
        return $request->getAttribute('locale', 'en');
    }

    protected function getLocaleService(Request $request): ?LocaleService
    {
        return $request->getAttribute('localeService');
    }
}