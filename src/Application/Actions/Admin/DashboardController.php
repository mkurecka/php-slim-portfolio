<?php

namespace App\Application\Actions\Admin;

use App\Domain\Blog\BlogRepository;
use App\Domain\Partner\PartnerLinkRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class DashboardController
{
    private PhpRenderer $renderer;
    private BlogRepository $blogRepository;
    private PartnerLinkRepository $partnerLinkRepository;

    public function __construct(PhpRenderer $renderer, BlogRepository $blogRepository, PartnerLinkRepository $partnerLinkRepository, ContainerInterface $container)
    {
        $this->renderer = $container->get('admin.renderer');
        $this->blogRepository = $blogRepository;
        $this->partnerLinkRepository = $partnerLinkRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $blogPosts = $this->blogRepository->findAll();
        $partnerLinks = $this->partnerLinkRepository->findAll();
        
        return $this->renderer->render($response, 'admin/dashboard.php', [
            'title' => 'Admin Dashboard',
            'blogPosts' => $blogPosts,
            'partnerLinks' => $partnerLinks
        ]);
    }
}
