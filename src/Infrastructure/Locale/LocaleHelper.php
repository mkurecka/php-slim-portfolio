<?php

declare(strict_types=1);

namespace App\Infrastructure\Locale;

class LocaleHelper
{
    public static function getLocalizedRoute(array $templateData, string $route, array $params = []): string
    {
        if (!isset($templateData['localeService']) || !($templateData['localeService'] instanceof LocaleService)) {
            return '/' . $route;
        }

        $localeService = $templateData['localeService'];
        return $localeService->generateLocalizedUrl($route, null, $params);
    }

    public static function getAlternateLanguageUrl(array $templateData, string $targetLocale): string
    {
        if (!isset($templateData['localeService']) || !($templateData['localeService'] instanceof LocaleService)) {
            return '/';
        }

        $localeService = $templateData['localeService'];
        $currentRoute = self::getCurrentRoute($templateData);
        
        return $localeService->generateLocalizedUrl($currentRoute, $targetLocale);
    }

    public static function getCurrentRoute(array $templateData): string
    {
        // Extract current route from request URI
        if (isset($_SERVER['REQUEST_URI'])) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (isset($templateData['localeService']) && ($templateData['localeService'] instanceof LocaleService)) {
                $localeService = $templateData['localeService'];
                $strippedPath = $localeService->stripLocaleFromPath($path);
                $segments = explode('/', trim($strippedPath, '/'));
                return $segments[0] ?? 'home';
            }
        }
        
        return 'home';
    }

    public static function isCurrentRoute(array $templateData, string $route): bool
    {
        $currentRoute = self::getCurrentRoute($templateData);
        return $currentRoute === $route;
    }
}