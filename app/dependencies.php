<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Auth\AuthService;
use App\Domain\Blog\BlogRepository;
use App\Domain\CV\CVService;
use App\Domain\Partner\PartnerLinkRepository;
use App\Domain\Promo\BlogPromoRepository;
use App\Infrastructure\Markdown\MarkdownService;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        
        // Template renderer
        PhpRenderer::class => function (ContainerInterface $c) {
            $renderer = new PhpRenderer(__DIR__ . '/../src/templates');
            $renderer->setLayout('layout.php');
            $renderer->addAttribute('title', 'Michal Kurecka | PHP & AI Developer');
            return $renderer;
        },
        
        // Admin template renderer
        'admin.renderer' => function (ContainerInterface $c) {
            $renderer = new PhpRenderer(__DIR__ . '/../src/templates');
            $renderer->setLayout('admin/layout.php');
            $renderer->addAttribute('title', 'Admin Dashboard');
            return $renderer;
        },
        
        // Domain services
        BlogRepository::class => function (ContainerInterface $c) {
            return new BlogRepository();
        },
        
        CVService::class => function (ContainerInterface $c) {
            return new CVService();
        },
        
        // Auth service
        AuthService::class => function (ContainerInterface $c) {
            return new AuthService();
        },
        
        // Markdown service
        MarkdownService::class => function (ContainerInterface $c) {
            return new MarkdownService();
        },
        
        // Blog Action
        App\Application\Actions\Blog\BlogAction::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Blog\BlogAction(
                $c->get(PhpRenderer::class),
                $c->get(BlogRepository::class),
                $c->get(MarkdownService::class),
                $c->get(App\Infrastructure\Content\SiteContentService::class),
                $c,
                $c->get(SettingsInterface::class)
            );
        },
        
        // Site Content service
        App\Infrastructure\Content\SiteContentService::class => function (ContainerInterface $c) {
            return new App\Infrastructure\Content\SiteContentService();
        },
        
        // Contact repository
        App\Domain\Contact\ContactRepository::class => function (ContainerInterface $c) {
            return new App\Domain\Contact\ContactRepository();
        },
        
        // Webhook service
        App\Infrastructure\Webhook\WebhookService::class => function (ContainerInterface $c) {
            return new App\Infrastructure\Webhook\WebhookService(
                $c->get(App\Infrastructure\Content\SiteContentService::class),
                $c->get(BlogRepository::class)
            );
        },
        
        // WebhookController
        App\Application\Actions\Admin\WebhookController::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Admin\WebhookController(
                $c->get('admin.renderer'),
                $c->get(App\Infrastructure\Content\SiteContentService::class),
                $c->get(App\Infrastructure\Webhook\WebhookService::class),
                $c->get(BlogRepository::class)
            );
        },
        
        // Partner Link Repository
        PartnerLinkRepository::class => function (ContainerInterface $c) {
            return new PartnerLinkRepository();
        },
        
        // Partner Controller
        App\Application\Actions\Admin\PartnerController::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Admin\PartnerController(
                $c->get('admin.renderer'),
                $c->get(PartnerLinkRepository::class)
            );
        },
        
        // Dashboard Controller
        App\Application\Actions\Admin\DashboardController::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Admin\DashboardController(
                $c->get('admin.renderer'),
                $c->get(BlogRepository::class),
                $c->get(PartnerLinkRepository::class),
                $c
            );
        },
        
        // Partner Redirect Action
        App\Application\Actions\Partner\PartnerRedirectAction::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Partner\PartnerRedirectAction(
                $c->get(PartnerLinkRepository::class)
            );
        },
        
        // Home Action
        App\Application\Actions\HomeAction::class => function (ContainerInterface $c) {
            return new App\Application\Actions\HomeAction(
                $c->get(PhpRenderer::class),
                $c->get(BlogRepository::class),
                $c->get(App\Infrastructure\Content\SiteContentService::class),
                $c->get(PartnerLinkRepository::class),
                $c->get(SettingsInterface::class)
            );
        },
        
        // One Column Action
        App\Application\Actions\OneColumnAction::class => function (ContainerInterface $c) {
            return new App\Application\Actions\OneColumnAction(
                $c->get(PhpRenderer::class),
                $c->get(BlogRepository::class),
                $c->get(PartnerLinkRepository::class),
                $c->get(App\Infrastructure\Content\SiteContentService::class)
            );
        },
        
        // Blog Promo Repository
        BlogPromoRepository::class => function (ContainerInterface $c) {
            return new BlogPromoRepository();
        },
        
        // Promo Controller
        App\Application\Actions\Admin\PromoController::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Admin\PromoController(
                $c->get('admin.renderer'),
                $c->get(BlogPromoRepository::class)
            );
        },
        
        // Settings Controller
        App\Application\Actions\Admin\SettingsController::class => function (ContainerInterface $c) {
            return new App\Application\Actions\Admin\SettingsController(
                $c->get('admin.renderer'),
                $c->get(SettingsInterface::class)
            );
        },
    ]);
};
