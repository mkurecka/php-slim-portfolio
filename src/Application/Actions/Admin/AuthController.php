<?php

namespace App\Application\Actions\Admin;

use App\Auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class AuthController
{
    private PhpRenderer $renderer;
    private AuthService $authService;

    public function __construct(PhpRenderer $renderer, AuthService $authService)
    {
        $this->renderer = $renderer;
        $this->authService = $authService;
    }

    public function loginPage(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'admin/login.php');
    }
    
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        
        $token = $this->authService->login($username, $password);
        
        if ($token) {
            // Set JWT as a cookie with 30-day expiration
            $expires = gmdate('D, d M Y H:i:s T', time() + 2592000);
            $response = $response->withHeader('Set-Cookie', 'jwt=' . $token . '; Path=/; HttpOnly; SameSite=Strict; Expires=' . $expires);
            
            // Redirect to admin dashboard
            return $response->withHeader('Location', '/admin/dashboard')
                            ->withStatus(302);
        }
        
        // Failed login
        return $this->renderer->render($response, 'admin/login.php', [
            'error' => 'Invalid username or password.'
        ]);
    }
    
    public function logout(Request $request, Response $response): Response
    {
        // Clear the JWT cookie
        $response = $response->withHeader('Set-Cookie', 'jwt=; Path=/; HttpOnly; SameSite=Strict; Expires=Thu, 01 Jan 1970 00:00:00 GMT');
        
        // Redirect to login page
        return $response->withHeader('Location', '/admin/login')
                        ->withStatus(302);
    }
}
