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
            $oldFormData = $_POST;

            // chiavi da escludere per la visualizzazione nel browser
            $excludedFields = ['password', "confirmPassword"];

            // toglie le $escludedFields dall'array ritornato da $_POST
            $formattedFormData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields)
            );
            // salva i dati dell'array relativi ai campi dei form già inseriti nella sessione
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $formattedFormData;

            // fa il redirect allo stesso indirizzo da cui è stato inviato il form
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}
