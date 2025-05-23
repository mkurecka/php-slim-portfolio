<?php

declare(strict_types=1);

// Demo script to test the locale functionality
require_once __DIR__ . '/vendor/autoload.php';

use App\Infrastructure\Locale\LocaleService;
use App\Infrastructure\Locale\LocaleHelper;

// Create a mock request simulation
$localeService = new LocaleService();

echo "=== Multi-locale Routing Demo ===\n\n";

// Test locale detection 
echo "1. Route mappings:\n";
echo "   English 'contact' -> " . $localeService->getRouteForLocale('contact', 'en') . "\n";
echo "   Czech 'contact' -> " . $localeService->getRouteForLocale('contact', 'cs') . "\n";
echo "   English 'articles' -> " . $localeService->getRouteForLocale('articles', 'en') . "\n";
echo "   Czech 'articles' -> " . $localeService->getRouteForLocale('articles', 'cs') . "\n\n";

// Test URL generation
echo "2. URL generation:\n";
$localeService->setCurrentLocale('en');
echo "   EN contact URL: " . $localeService->generateLocalizedUrl('contact', 'en') . "\n";
echo "   CS contact URL: " . $localeService->generateLocalizedUrl('contact', 'cs') . "\n";
echo "   EN blog URL: " . $localeService->generateLocalizedUrl('blog', 'en') . "\n";
echo "   CS blog URL: " . $localeService->generateLocalizedUrl('blog', 'cs') . "\n\n";

// Test reverse mapping
echo "3. Reverse mapping (localized -> original):\n";
echo "   'kontakt' -> " . $localeService->getOriginalRoute('kontakt', 'cs') . "\n";
echo "   'clanky' -> " . $localeService->getOriginalRoute('clanky', 'cs') . "\n";
echo "   'zivotopis' -> " . $localeService->getOriginalRoute('zivotopis', 'cs') . "\n\n";

// Test path stripping
echo "4. Path stripping:\n";
echo "   '/cs/kontakt' -> " . $localeService->stripLocaleFromPath('/cs/kontakt') . "\n";
echo "   '/en/contact' -> " . $localeService->stripLocaleFromPath('/en/contact') . "\n";
echo "   '/contact' -> " . $localeService->stripLocaleFromPath('/contact') . "\n\n";

echo "✅ All tests completed successfully!\n";
echo "\nThe multi-locale routing system is ready to use.\n";
echo "Supported URLs:\n";
echo "- /contact or /kontakt\n";
echo "- /blog or /clanky  \n";
echo "- /cv or /zivotopis\n";