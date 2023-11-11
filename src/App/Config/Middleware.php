<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Middleware\{TemplateDataMiddleware, ValidationExceptionMiddleware, SessionMiddleware, FlashMiddleware};

function registerMiddleware(App $app)
{
    $app->addMiddleware(TemplateDataMiddleware::class);
    $app->addMiddleware(ValidationExceptionMiddleware::class);

    // FlashMiddleware da aggiungere prima dell'avvio della sessione in modo da essere eseguita dopo
    $app->addMiddleware(FlashMiddleware::class);

    // SessionMiddleware va registrata all'ultimo per essere eseguita per prima
    $app->addMiddleware(SessionMiddleware::class);
}
