<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{HomeController, AboutController, AuthController};

use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

function registerRoutes(App $app)
{
    $app->get("/", [HomeController::class, "home"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->get("/about", [AboutController::class, "about"]);

    $app->get("/register", [AuthController::class, "getRegister"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    $app->post("/register", [AuthController::class, "registerForm"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    $app->get("/login", [AuthController::class, "getLogin"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    $app->post("/login", [AuthController::class, "login"])->addRouteMiddleware(GuestOnlyMiddleware::class);
}
