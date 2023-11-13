<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class CSRFTokenMiddleware implements MiddlewareInterface
{
    public function __construct(private TemplateEngine $view)
    {
    }

    public function process(callable $next)
    {
        // crea CSRFToken se non esiste, random_bytes ritorna un codice binario che non può essere visualizzato nel browser 
        // con bin2hex viene convertito in text (hex);
        $_SESSION['CSRFToken'] = $_SESSION['CSRFToken'] ?? bin2hex(random_bytes(32));

        $this->view->addGlobal("CSRFToken", $_SESSION['CSRFToken']);

        $next();
    }
}
