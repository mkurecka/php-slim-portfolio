<?php

namespace App\Application\Actions;

use App\Application\Settings\SettingsInterface;
use App\Domain\Blog\BlogRepository;
use App\Domain\Partner\PartnerLinkRepository;
use App\Infrastructure\Content\SiteContentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class HomeAction extends BaseAction
{
    private BlogRepository $blogRepository;
    private PartnerLinkRepository $partnerLinkRepository;
    private SettingsInterface $settings;

    public function __construct(
        PhpRenderer $renderer, 
        BlogRepository $blogRepository,
        SiteContentService $contentService,
        PartnerLinkRepository $partnerLinkRepository,
        SettingsInterface $settings
    ) {
        parent::__construct($renderer, $contentService);
        $this->blogRepository = $blogRepository;
        $this->partnerLinkRepository = $partnerLinkRepository;
        $this->settings = $settings;
    }

    public function __invoke(Request $request, Response $response): Response
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
        
        // Get video posts
        $videoPosts = array_filter($posts, function($post) {
            return $post->hasYoutubeVideo();
        });
        
        // Get partner links
        $partnerLinks = $this->partnerLinkRepository->findAll();
        
        // Get the site content
        $globalContent = $this->contentService->getContent('global');
        $homeContent = $this->contentService->getContent('home');
        $profileContent = $this->contentService->getContent('profile') ?? [];
        
        // Prepare social links
        $socialLinks = [];
        if (isset($globalContent['social_media'])) {
            $socialLinks = $globalContent['social_media'];
        }
        
        // Check which template to use
        $templateSetting = $this->settings->get('templates')['site'] ?? 'default';
        
        if ($templateSetting === 'one-column') {
            // Use one-column template with one-column layout (no menu)
            $renderer = clone $this->renderer;
            $renderer->setLayout('one-column-layout.php');
            
            return $renderer->render($response, 'one-column.php', [
                'title' => ($profileContent['name'] ?? 'Profile') . ' | ' . ($globalContent['site_name'] ?? 'Michal Kurecka'),
                'name' => $profileContent['name'] ?? null,
                'title' => $profileContent['title'] ?? null,
                'profile_image' => $profileContent['profile_image'] ?? null,
                'about_content' => $profileContent['about_content'] ?? null,
                'social_links' => $socialLinks,
                'blog_posts' => array_slice($posts, 0, 5), // Show only the 5 most recent posts
                'video_posts' => array_slice($videoPosts, 0, 3), // Show only the 3 most recent video posts
                'partner_links' => $partnerLinks,
                'site_content' => $this->contentService->getAllContent()
            ]);
        } else {
            // Use default template
            return $this->renderWithContent($response, 'home.php', [
                'title' => $globalContent['site_title'] ?? 'Michal Kurecka | PHP & AI Developer',
                'latestPosts' => array_slice($posts, 0, 2),
                'content' => $homeContent
            ]);
        }
    }
}
