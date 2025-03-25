<?php

namespace App\Application\Actions;

use App\Domain\Blog\BlogRepository;
use App\Domain\Partner\PartnerLinkRepository;
use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class OneColumnAction extends BaseAction
{
    private BlogRepository $blogRepository;
    private PartnerLinkRepository $partnerLinkRepository;

    public function __construct(
        PhpRenderer $renderer, 
        BlogRepository $blogRepository,
        PartnerLinkRepository $partnerLinkRepository,
        SiteContentService $contentService
    ) {
        parent::__construct($renderer, $contentService);
        $this->blogRepository = $blogRepository;
        $this->partnerLinkRepository = $partnerLinkRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        // Get all blog posts
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
        
        // Get blog posts with videos
        $videoPosts = array_filter($posts, function($post) {
            return $post->hasYoutubeVideo();
        });
        
        // Get partner links
        $partnerLinks = $this->partnerLinkRepository->findAll();
        
        // Get the site content
        $globalContent = $this->contentService->getContent('global');
        $profileContent = $this->contentService->getContent('profile') ?? [];
        $templateSettings = $this->contentService->getContent('one-column') ?? [];
        
        // Prepare social links
        $socialLinks = [];
        if (isset($globalContent['social_media'])) {
            $socialLinks = $globalContent['social_media'];
        }
        
        return $this->renderWithContent($response, 'one-column.php', [
            'title' => ($profileContent['name'] ?? 'Profile') . ' | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
            'name' => $profileContent['name'] ?? null,
            'title' => $profileContent['title'] ?? null,
            'profile_image' => $profileContent['profile_image'] ?? null,
            'about_content' => $profileContent['about_content'] ?? null,
            'social_links' => $socialLinks,
            'blog_posts' => $posts, // Pass all posts, the template will handle limiting
            'video_posts' => $videoPosts, // Pass all video posts, the template will handle limiting
            'partner_links' => $partnerLinks,
            'template_settings' => $templateSettings // Pass the template settings
        ]);
    }
}
