<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Auth\AuthService;
use App\Domain\Blog\BlogRepository;
use App\Domain\CV\CVService;
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
                $c->get(App\Infrastructure\Content\SiteContentService::class)
            );
        },
    ]);
};