<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

// controlla i dati richiesti nel template della pagina
class TemplateDataMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TemplateEngine $view
    ) {
    }

    public function process(callable $next)
    {
        $this->view->addGlobal("title", "Expense Tracking App");

        $next();
    }
}
