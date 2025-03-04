<?php

namespace App\Application\Actions\Blog;

use App\Domain\Blog\BlogRepository;
use App\Infrastructure\Markdown\MarkdownService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class BlogAction
{
    private PhpRenderer $renderer;
    private BlogRepository $blogRepository;
    private MarkdownService $markdownService;

    public function __construct(
        PhpRenderer $renderer, 
        BlogRepository $blogRepository,
        MarkdownService $markdownService
    ) {
        $this->renderer = $renderer;
        $this->blogRepository = $blogRepository;
        $this->markdownService = $markdownService;
    }

    public function listPosts(Request $request, Response $response): Response
    {
        $posts = $this->blogRepository->findAll();
        
        // Filter out future posts
        $currentDate = date('Y-m-d');
        $posts = array_filter($posts, function($post) use ($currentDate) {
            return $post->getDate() <= $currentDate;
        });
        
        // Sort by date (newest first)
        usort($posts, function($a, $b) {
            return strtotime($b->getDate()) - strtotime($a->getDate());
        });
        
        return $this->renderer->render($response, 'blog/index.php', [
            'title' => 'Blog | ' . ($_ENV['SITE_NAME'] ?? 'Michal Kurecka'),
            'posts' => $posts
        ]);
    }
    
    public function showPost(Request $request, Response $response, array $args): Response
    {
        $slug = $args['slug'];
        $post = $this->blogRepository->findBySlug($slug);
        
        if (!$post) {
            return $response->withStatus(404);
        }
        
        return $this->renderer->render($response, 'blog/post.php', [
            'title' => $post->getTitle() . ' | ' . ($_ENV['SITE_NAME'] ?? 'Michal Kurecka'),
            'post' => $post,
            'markdownService' => $this->markdownService
        ]);
    }
}