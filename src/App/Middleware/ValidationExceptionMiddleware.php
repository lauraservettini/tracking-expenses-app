<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

// middleware per gestire l'errore tramite PRG(POST; redirect, GET)
class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            // salva i dati dell'array relativi ai campi dei form già inseriti nella sessione
            $_SESSION['errors'] = $e->errors;

            // fa il redirect allo stesso indirizzo da cui è stato inviato il form
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}
