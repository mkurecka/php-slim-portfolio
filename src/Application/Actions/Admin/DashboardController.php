<?php

namespace App\Application\Actions\Admin;

use App\Domain\Blog\BlogRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class DashboardController
{
    private PhpRenderer $renderer;
    private BlogRepository $blogRepository;

    public function __construct(PhpRenderer $renderer, BlogRepository $blogRepository, ContainerInterface $container)
    {
        $this->renderer = $container->get('admin.renderer');
        $this->blogRepository = $blogRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $blogPosts = $this->blogRepository->findAll();
        
        return $this->renderer->render($response, 'admin/dashboard.php', [
            'title' => 'Admin Dashboard',
            'blogPosts' => $blogPosts
        ]);
    }
}