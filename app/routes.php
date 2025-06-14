<?php

declare(strict_types=1);

use App\Application\Actions\Blog\BlogAction;
use App\Application\Actions\ContactAction;
use App\Application\Actions\CV\CVAction;
use App\Application\Actions\HomeAction;
use App\Application\Actions\OneColumnAction;
use App\Application\Actions\Admin\AuthController;
use App\Application\Actions\Admin\BlogController;
use App\Application\Actions\Admin\ContactController;
use App\Application\Actions\Admin\CVController;
use App\Application\Actions\Admin\DashboardController;
use App\Application\Actions\Admin\PartnerController;
use App\Application\Actions\Admin\PromoController;
use App\Application\Actions\Admin\SettingsController;
use App\Application\Actions\Admin\SiteContentAction;
use App\Application\Actions\Admin\WebhookController;
use App\Application\Actions\Partner\PartnerRedirectAction;
use App\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\PhpRenderer;

return function (App $app) {
    // Main website routes
    $app->get('/', HomeAction::class);
    
    // Blog routes - support both 'blog' and 'clanky'
    $app->get('/blog', [BlogAction::class, 'listPosts']);
    $app->get('/clanky', [BlogAction::class, 'listPosts']);
    $app->get('/blog/{slug}', [BlogAction::class, 'showPost']);
    $app->get('/clanky/{slug}', [BlogAction::class, 'showPost']);
    
    // CV routes - support both 'cv' and 'zivotopis'
    $app->get('/cv', [CVAction::class, 'showCV']);
    $app->get('/zivotopis', [CVAction::class, 'showCV']);
    
    // Contact routes - support both 'contact' and 'kontakt'
    $app->get('/contact', [ContactAction::class, 'showForm']);
    $app->get('/kontakt', [ContactAction::class, 'showForm']);
    $app->post('/contact', [ContactAction::class, 'handleForm']);
    $app->post('/kontakt', [ContactAction::class, 'handleForm']);
    
    // The template is now controlled by the site settings
    // $app->get('/profile', OneColumnAction::class);
    
    // Admin routes
    $app->get('/admin/login', [AuthController::class, 'loginPage']);
    $app->post('/admin/login', [AuthController::class, 'login']);
    $app->get('/admin/logout', [AuthController::class, 'logout']);
    
    // Admin root route
    $app->get('/admin', function (Request $request, Response $response) {
        return $response->withHeader('Location', '/admin/dashboard')->withStatus(302);
    });
    
    // Partner redirect route - must be after admin routes to avoid conflicts
    $app->get('/{slug}', PartnerRedirectAction::class);
    
    // API Webhook routes
    $app->post('/api/webhook/blog', [WebhookController::class, 'handleBlogWebhook']);
    
    // Route to serve images from data directory
    $app->get('/data/images/{filename}', function (Request $request, Response $response, array $args) {
        $filename = $args['filename'];
        $filePath = __DIR__ . '/../data/images/' . $filename;
        
        // Security check - prevent directory traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
            return $response->withStatus(404);
        }
        
        // Check if file exists
        if (!file_exists($filePath) || !is_file($filePath)) {
            return $response->withStatus(404);
        }
        
        // Get file info
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);
        
        // Only serve image files
        if (strpos($mimeType, 'image/') !== 0) {
            return $response->withStatus(404);
        }
        
        // Read and serve the file
        $fileContent = file_get_contents($filePath);
        $response->getBody()->write($fileContent);
        
        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Length', (string)filesize($filePath))
            ->withHeader('Cache-Control', 'public, max-age=31536000'); // Cache for 1 year
    });
    
    // Protected admin routes
    $app->group('/admin', function ($group) {
        $group->get('/dashboard', [DashboardController::class, 'index']);
        
        // Blog management
        $group->get('/blog', [BlogController::class, 'index']);
        $group->get('/blog/new', [BlogController::class, 'newPost']);
        $group->post('/blog/create', [BlogController::class, 'createPost']);
        $group->get('/blog/edit/{id}', [BlogController::class, 'editPost']);
        $group->post('/blog/update/{id}', [BlogController::class, 'updatePost']);
        $group->get('/blog/delete/{id}', [BlogController::class, 'deletePost']);
        
        // CV management
        $group->get('/cv', [CVController::class, 'edit']);
        $group->post('/cv/update', [CVController::class, 'update']);
        
        // Site content management
        $group->get('/content', [SiteContentAction::class, 'listSections']);
        $group->get('/content/{section}', [SiteContentAction::class, 'editSection']);
        $group->post('/content/{section}', [SiteContentAction::class, 'updateSection']);
        
        // Contact submissions management
        $group->get('/contact', [ContactController::class, 'index']);
        $group->get('/contact/delete/{id}', [ContactController::class, 'delete']);
        
        // Webhook management
        $group->get('/webhook', [WebhookController::class, 'settings']);
        $group->post('/webhook', [WebhookController::class, 'updateSettings']);
        $group->get('/webhook/clear-logs', [WebhookController::class, 'clearLogs']);
        
        // Partner links management
        $group->get('/partner', [PartnerController::class, 'index']);
        $group->get('/partner/new', [PartnerController::class, 'newLink']);
        $group->post('/partner/create', [PartnerController::class, 'createLink']);
        $group->get('/partner/edit/{id}', [PartnerController::class, 'editLink']);
        $group->post('/partner/update/{id}', [PartnerController::class, 'updateLink']);
        $group->get('/partner/delete/{id}', [PartnerController::class, 'deleteLink']);
        
        // Blog promo management
        $group->get('/promo', [PromoController::class, 'edit']);
        $group->post('/promo/update', [PromoController::class, 'update']);
        
        // Template settings management
        $group->get('/settings', [SettingsController::class, 'index']);
        $group->post('/settings/update', [SettingsController::class, 'update']);
    })->add(AuthMiddleware::class);
    
    // Error handling for 404 Not Found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response): Response {
        $renderer = new PhpRenderer(__DIR__ . '/../src/templates');
        return $renderer->render($response->withStatus(404), 'error/404.php', [
            'title' => '404 - Page Not Found'
        ]);
    });
};
