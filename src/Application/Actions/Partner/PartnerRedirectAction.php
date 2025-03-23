<?php

namespace App\Application\Actions\Partner;

use App\Domain\Partner\PartnerLinkRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PartnerRedirectAction
{
    private PartnerLinkRepository $partnerLinkRepository;

    public function __construct(PartnerLinkRepository $partnerLinkRepository)
    {
        $this->partnerLinkRepository = $partnerLinkRepository;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $slug = $args['slug'];
        
        // Check if the slug is a reserved path (like 'admin', 'blog', 'cv', etc.)
        $reservedPaths = ['admin', 'blog', 'cv', 'contact', 'api', 'webhook'];
        if (in_array($slug, $reservedPaths)) {
            // This is a reserved path, not a partner link
            return $response->withStatus(404);
        }
        
        $link = $this->partnerLinkRepository->findBySlug($slug);
        
        if (!$link) {
            // Link not found, redirect to homepage or show 404
            return $response->withStatus(404);
        }
        
        // Increment click count
        $this->partnerLinkRepository->incrementClickCount($slug);
        
        // Perform 301 redirect to target URL
        return $response->withHeader('Location', $link->getTargetUrl())
                       ->withStatus(301);
    }
}
