<?php

declare(strict_types=1);

use App\Application\Middleware\LocaleMiddleware;
use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(LocaleMiddleware::class);
};
