<?php

namespace App\Middleware;

use App\Auth\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware implements MiddlewareInterface
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = $this->extractToken($request);
        
        if ($token && $this->authService->validateToken($token)) {
            return $handler->handle($request);
        }
        
        $response = new SlimResponse();
        return $response->withStatus(401)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->getStream(['error' => 'Unauthorized']));
    }
    
    private function extractToken(Request $request): ?string
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        
        $cookies = $request->getCookieParams();
        if (isset($cookies['jwt'])) {
            return $cookies['jwt'];
        }
        
        return null;
    }
    
    private function getStream(array $data): \Psr\Http\Message\StreamInterface
    {
        $json = json_encode($data);
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $json);
        rewind($stream);
        
        return new \Slim\Psr7\Stream($stream);
    }
}