<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Middleware\{
    TemplateDataMiddleware,
    ValidationExceptionMiddleware,
    SessionMiddleware,
    FlashMiddleware,
    CSRFTokenMiddleware,
    CSRFGuardMiddleware
};

function registerMiddleware(App $app)
{
    // CSRFGuardMiddleware verifica se c'è il CSRFToken 
    // e se è uguale al CSRFToken creato da CSRFTokenMiddleware per i metodi POST, PATCH e DELETE
    $app->addMiddleware(CSRFGuardMiddleware::class);

    // CSRFTokenMiddleware aggiunge il CSRFToken
    $app->addMiddleware(CSRFTokenMiddleware::class);

    // TemplateDataMiddleware aggiunge le variabili globali
    $app->addMiddleware(TemplateDataMiddleware::class);

    // ValidationExceptionMiddleware per gestire l'errore tramite PRG(POST; redirect, GET)
    $app->addMiddleware(ValidationExceptionMiddleware::class);

    // FlashMiddleware da aggiungere prima dell'avvio della sessione in modo da essere eseguita dopo
    $app->addMiddleware(FlashMiddleware::class);

    // SessionMiddleware va registrata all'ultimo per essere eseguita per prima
    $app->addMiddleware(SessionMiddleware::class);
}
