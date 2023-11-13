<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class CSRFGuardMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        // estrae il metodo della richiesta
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        // definisce i metodi su cui calidare il CSRFToken
        $validMethods = ['POST', 'PATCH', 'DELETE'];

        if (!in_array($requestMethod, $validMethods)) {
            $next();
            return;
        }

        if ($_SESSION['CSRFToken'] !== $_POST['CSRFToken']) {
            redirectTo("/login");
        }

        unset($_SESSION['CSRFToken']);

        $next();
    }
}
