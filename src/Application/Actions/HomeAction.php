<?php

namespace App\Application\Actions;

use App\Domain\Blog\BlogRepository;
use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class HomeAction extends BaseAction
{
    private BlogRepository $blogRepository;

    public function __construct(
        PhpRenderer $renderer, 
        BlogRepository $blogRepository,
        SiteContentService $contentService
    ) {
        parent::__construct($renderer, $contentService);
        $this->blogRepository = $blogRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $posts = $this->blogRepository->findAll();
        
        // Filter out future posts
        $currentDate = date('Y-m-d');
        $posts = array_filter($posts, function($post) use ($currentDate) {
            return $post->getDate() <= $currentDate;
        });
        
        // Sort by date (newest first) and take the latest 2
        usort($posts, function($a, $b) {
            return strtotime($b->getDate()) - strtotime($a->getDate());
        });
        $latestPosts = array_slice($posts, 0, 2);
        
        // Get the site content
        $globalContent = $this->contentService->getContent('global');
        $homeContent = $this->contentService->getContent('home');
        
        return $this->renderWithContent($response, 'home.php', [
            'title' => $globalContent['site_title'] ?? 'Michal Kurecka | PHP & AI Developer',
            'latestPosts' => $latestPosts,
            'content' => $homeContent
        ]);
    }
}