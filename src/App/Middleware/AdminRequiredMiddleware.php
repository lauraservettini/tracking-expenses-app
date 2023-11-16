<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class AdminRequiredMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        if (empty($_SESSION['user'])) {
            redirectTo("/login");
        }

        if (empty($_SESSION['isAdmin'])) {
            redirectTo("/");
        } else if (!$_SESSION['isAdmin']) {
            redirectTo("/");
        }

        $next();
    }
}
