<?php

namespace App\Application\Actions\CV;

use App\Application\Actions\BaseAction;
use App\Domain\CV\CVService;
use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class CVAction extends BaseAction
{
    private CVService $cvService;

    public function __construct(
        PhpRenderer $renderer, 
        CVService $cvService,
        SiteContentService $contentService
    ) {
        parent::__construct($renderer, $contentService);
        $this->cvService = $cvService;
    }

    public function showCV(Request $request, Response $response): Response
    {
        $cv = $this->cvService->getCV();
        
        // Get global content for the site name
        $globalContent = $this->contentService->getContent('global');
        
        return $this->renderWithContent($response, 'cv/index.php', [
            'title' => 'CV | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
            'cv' => $cv
        ]);
    }
}