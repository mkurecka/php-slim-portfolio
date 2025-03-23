<?php

namespace App\Application\Actions\Blog;

use App\Application\Actions\BaseAction;
use App\Domain\Blog\BlogRepository;
use App\Infrastructure\Content\SiteContentService;
use App\Infrastructure\Markdown\MarkdownService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class BlogAction extends BaseAction
{
    private BlogRepository $blogRepository;
    private MarkdownService $markdownService;
    private ContainerInterface $container;

    public function __construct(
        PhpRenderer $renderer, 
        BlogRepository $blogRepository,
        MarkdownService $markdownService,
        SiteContentService $contentService,
        ContainerInterface $container
    ) {
        parent::__construct($renderer, $contentService);
        $this->blogRepository = $blogRepository;
        $this->markdownService = $markdownService;
        $this->container = $container;
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
        
        // Get global content for the site name
        $globalContent = $this->contentService->getContent('global');
        
        return $this->renderWithContent($response, 'blog/index.php', [
            'title' => 'Blog | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
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
        
        // Get global content for the site name
        $globalContent = $this->contentService->getContent('global');
        
        return $this->renderWithContent($response, 'blog/post.php', [
            'title' => $post->getTitle() . ' | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
            'post' => $post,
            'markdownService' => $this->markdownService,
            'container' => $this->container
        ]);
    }
}
