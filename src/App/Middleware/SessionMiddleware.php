<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        // verifica prima se la session è ACTIVE, crea una Exception
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already active.");
        }

        // verifica se l'header è già stato inviato al browser
        if (headers_sent($filename, $line)) {
            throw new SessionException("Header already sent. Consider enabling putput buffering. Data outputted from {$filename} - Line: {$line}");
        }

        session_set_cookie_params(
            [
                // questa opzione previene che i cookies siano inviati a connessioni non sicure
                'secure' => $_ENV['APP_ENV'] === "production",
                'httponly' => true,
                'samesite' => "lax",
                'lifetime' => 300,

            ]
        );


        session_start();

        $next();

        session_write_close();
    }
}
