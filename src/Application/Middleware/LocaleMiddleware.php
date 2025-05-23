<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Infrastructure\Locale\LocaleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LocaleMiddleware implements Middleware
{
    private LocaleService $localeService;

    public function __construct(LocaleService $localeService)
    {
        $this->localeService = $localeService;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        // Detect locale from request
        $locale = $this->localeService->detectLocaleFromRequest($request);
        $this->localeService->setCurrentLocale($locale);

        // Strip locale from path if present and modify request
        $uri = $request->getUri();
        $path = $uri->getPath();
        $strippedPath = $this->localeService->stripLocaleFromPath($path);
        
        if ($strippedPath !== $path) {
            // Create new URI with stripped path
            $newUri = $uri->withPath($strippedPath);
            $request = $request->withUri($newUri);
        }

        // Add locale info to request attributes
        $request = $request->withAttribute('locale', $locale);
        $request = $request->withAttribute('localeService', $this->localeService);

        return $handler->handle($request);
    }
}