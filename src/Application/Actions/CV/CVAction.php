<?php

namespace App\Application\Actions\CV;

use App\Domain\CV\CVService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class CVAction
{
    private PhpRenderer $renderer;
    private CVService $cvService;

    public function __construct(PhpRenderer $renderer, CVService $cvService)
    {
        $this->renderer = $renderer;
        $this->cvService = $cvService;
    }

    public function showCV(Request $request, Response $response): Response
    {
        $cv = $this->cvService->getCV();
        
        return $this->renderer->render($response, 'cv/index.php', [
            'title' => 'CV | ' . ($_ENV['SITE_NAME'] ?? 'Michal Kurecka'),
            'cv' => $cv
        ]);
    }
}