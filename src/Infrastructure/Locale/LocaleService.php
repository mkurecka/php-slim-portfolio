<?php

declare(strict_types=1);

namespace App\Infrastructure\Locale;

use Psr\Http\Message\ServerRequestInterface as Request;

class LocaleService
{
    private string $currentLocale = 'en';
    private string $defaultLocale = 'en';
    private array $supportedLocales = ['en', 'cs'];
    
    private array $routeMap = [
        'en' => [
            'contact' => 'contact',
            'blog' => 'blog',
            'cv' => 'cv',
            'articles' => 'articles',
        ],
        'cs' => [
            'contact' => 'kontakt',
            'blog' => 'blog',
            'cv' => 'zivotopis',
            'articles' => 'clanky',
        ]
    ];

    public function detectLocaleFromRequest(Request $request): string
    {
        $uri = $request->getUri()->getPath();
        
        // Check if URL starts with locale prefix (e.g., /cs/kontakt)
        if (preg_match('#^/([a-z]{2})(/.*)?$#', $uri, $matches)) {
            $locale = $matches[1];
            if (in_array($locale, $this->supportedLocales)) {
                return $locale;
            }
        }
        
        // Check Accept-Language header as fallback
        $acceptLanguage = $request->getHeaderLine('Accept-Language');
        if ($acceptLanguage) {
            $preferredLanguages = $this->parseAcceptLanguage($acceptLanguage);
            foreach ($preferredLanguages as $lang) {
                if (in_array($lang, $this->supportedLocales)) {
                    return $lang;
                }
            }
        }
        
        return $this->defaultLocale;
    }

    public function setCurrentLocale(string $locale): void
    {
        if (in_array($locale, $this->supportedLocales)) {
            $this->currentLocale = $locale;
        }
    }

    public function getCurrentLocale(): string
    {
        return $this->currentLocale;
    }

    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    public function getRouteForLocale(string $route, string $locale = null): string
    {
        $locale = $locale ?? $this->currentLocale;
        
        // First try to find the route in the current locale
        if (isset($this->routeMap[$locale][$route])) {
            return $this->routeMap[$locale][$route];
        }
        
        // Try to find the original route key by searching all locales
        foreach ($this->routeMap as $localeKey => $routes) {
            foreach ($routes as $originalRoute => $localizedRoute) {
                if ($localizedRoute === $route) {
                    // Found the original route, now get it for the target locale
                    if (isset($this->routeMap[$locale][$originalRoute])) {
                        return $this->routeMap[$locale][$originalRoute];
                    }
                }
            }
        }
        
        return $route; // Return original if no mapping found
    }

    public function getOriginalRoute(string $localizedRoute, string $locale = null): string
    {
        $locale = $locale ?? $this->currentLocale;
        
        if (!isset($this->routeMap[$locale])) {
            return $localizedRoute;
        }
        
        foreach ($this->routeMap[$locale] as $original => $localized) {
            if ($localized === $localizedRoute) {
                return $original;
            }
        }
        
        return $localizedRoute;
    }

    public function generateLocalizedUrl(string $route, string $locale = null, array $params = []): string
    {
        $locale = $locale ?? $this->currentLocale;
        $localizedRoute = $this->getRouteForLocale($route, $locale);
        
        $url = '';
        if ($locale !== $this->defaultLocale) {
            $url .= '/' . $locale;
        }
        
        $url .= '/' . $localizedRoute;
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url = str_replace('{' . $key . '}', (string)$value, $url);
            }
        }
        
        return $url;
    }

    public function stripLocaleFromPath(string $path): string
    {
        if (preg_match('#^/([a-z]{2})(/.*)?$#', $path, $matches)) {
            $locale = $matches[1];
            if (in_array($locale, $this->supportedLocales)) {
                return $matches[2] ?? '/';
            }
        }
        
        return $path;
    }

    private function parseAcceptLanguage(string $acceptLanguage): array
    {
        $languages = [];
        $parts = explode(',', $acceptLanguage);
        
        foreach ($parts as $part) {
            $part = trim($part);
            if (preg_match('/^([a-z]{2})(-[A-Z]{2})?(;q=([0-9.]+))?$/', $part, $matches)) {
                $lang = $matches[1];
                $quality = isset($matches[4]) ? (float)$matches[4] : 1.0;
                $languages[$lang] = $quality;
            }
        }
        
        arsort($languages);
        return array_keys($languages);
    }
}