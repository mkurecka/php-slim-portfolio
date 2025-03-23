<?php

namespace App\Application\Actions\Admin;

use App\Domain\Partner\PartnerLink;
use App\Domain\Partner\PartnerLinkRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class PartnerController
{
    private PhpRenderer $renderer;
    private PartnerLinkRepository $partnerLinkRepository;

    public function __construct(PhpRenderer $renderer, PartnerLinkRepository $partnerLinkRepository)
    {
        $this->renderer = $renderer;
        $this->partnerLinkRepository = $partnerLinkRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $links = $this->partnerLinkRepository->findAll();
        
        return $this->renderer->render($response, 'admin/partner/index.php', [
            'title' => 'Manage Partner Links',
            'links' => $links
        ]);
    }
    
    public function newLink(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'admin/partner/form.php', [
            'title' => 'New Partner Link'
        ]);
    }
    
    public function createLink(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        // Validate slug
        $slug = trim($data['slug']);
        
        // Check if the slug is a reserved path
        $reservedPaths = ['admin', 'blog', 'cv', 'contact', 'api', 'webhook'];
        if (in_array($slug, $reservedPaths)) {
            return $this->renderer->render($response, 'admin/partner/form.php', [
                'title' => 'New Partner Link',
                'error' => 'This slug is a reserved path. Please choose a different one.',
                'formData' => $data
            ]);
        }
        
        // Validate slug uniqueness
        if ($this->partnerLinkRepository->slugExists($slug)) {
            return $this->renderer->render($response, 'admin/partner/form.php', [
                'title' => 'New Partner Link',
                'error' => 'This slug is already in use. Please choose a different one.',
                'formData' => $data
            ]);
        }
        
        $link = new PartnerLink(
            0, // ID will be set by repository
            $slug,
            $data['targetUrl'],
            $data['description'] ?? '',
            date('Y-m-d H:i:s'),
            0 // Initial click count
        );
        
        $this->partnerLinkRepository->save($link);
        
        return $response->withHeader('Location', '/admin/partner')
                        ->withStatus(302);
    }
    
    public function editLink(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $link = $this->partnerLinkRepository->findById($id);
        
        if (!$link) {
            return $response->withStatus(404);
        }
        
        return $this->renderer->render($response, 'admin/partner/form.php', [
            'title' => 'Edit Partner Link',
            'link' => $link
        ]);
    }
    
    public function updateLink(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $link = $this->partnerLinkRepository->findById($id);
        
        if (!$link) {
            return $response->withStatus(404);
        }
        
        $data = $request->getParsedBody();
        
        // Validate slug
        $slug = trim($data['slug']);
        
        // Check if the slug is a reserved path
        $reservedPaths = ['admin', 'blog', 'cv', 'contact', 'api', 'webhook'];
        if (in_array($slug, $reservedPaths)) {
            return $this->renderer->render($response, 'admin/partner/form.php', [
                'title' => 'Edit Partner Link',
                'link' => $link,
                'error' => 'This slug is a reserved path. Please choose a different one.',
                'formData' => $data
            ]);
        }
        
        // Validate slug uniqueness
        if ($slug !== $link->getSlug() && $this->partnerLinkRepository->slugExists($slug, $id)) {
            return $this->renderer->render($response, 'admin/partner/form.php', [
                'title' => 'Edit Partner Link',
                'link' => $link,
                'error' => 'This slug is already in use. Please choose a different one.',
                'formData' => $data
            ]);
        }
        
        $updatedLink = new PartnerLink(
            $id,
            $slug,
            $data['targetUrl'],
            $data['description'] ?? '',
            $link->getCreatedAt(),
            $link->getClickCount()
        );
        
        $this->partnerLinkRepository->save($updatedLink);
        
        return $response->withHeader('Location', '/admin/partner')
                        ->withStatus(302);
    }
    
    public function deleteLink(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $this->partnerLinkRepository->delete($id);
        
        return $response->withHeader('Location', '/admin/partner')
                        ->withStatus(302);
    }
}
