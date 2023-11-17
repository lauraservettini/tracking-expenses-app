<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{
    HomeController,
    AboutController,
    AuthController,
    TransactionController,
    ReceiptController,
    ErrorController,
    AdminController
};

use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware, AdminRequiredMiddleware};

function registerRoutes(App $app)
{
    $app->get("/about", [AboutController::class, "about"]);

    $app->get("/register", [AuthController::class, "getRegister"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    $app->post("/register", [AuthController::class, "registerForm"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    $app->get("/login", [AuthController::class, "getLogin"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    $app->post("/login", [AuthController::class, "login"])->addRouteMiddleware(GuestOnlyMiddleware::class);

    // routes con autenticazione
    $app->get("/", [HomeController::class, "home"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->get("/logout", [AuthController::class, "getLogout"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->get("/transaction", [TransactionController::class, "getCreate"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->post("/transaction", [TransactionController::class, "create"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->get("/transaction/{transaction}/", [TransactionController::class, "getEdit"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->post("/transaction/{transaction}/", [TransactionController::class, "update"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->delete("/transaction/{transaction}/", [TransactionController::class, "delete"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->get("/transaction/{transaction}/receipt", [ReceiptController::class, "getUpload"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->post("/transaction/{transaction}/receipt", [ReceiptController::class, "upload"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->get("/transaction/{transaction}/receipt/{receipt}", [ReceiptController::class, "download"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    $app->delete("/transaction/{transaction}/receipt/{receipt}", [ReceiptController::class, "delete"])->addRouteMiddleware(AuthRequiredMiddleware::class);

    // routes con autorizzazione admin
    $app->get("/admin", [AdminController::class, "home"])->addRouteMiddleware(AdminRequiredMiddleware::class);

    $app->get("/admin/logout", [AuthController::class, "getLogout"])->addRouteMiddleware(AdminqRequiredMiddleware::class);

    $app->get("/admin/users", [AdminController::class, "getUsers"])->addRouteMiddleware(AdminRequiredMiddleware::class);

    $app->get("/admin/users/{user}", [AdminController::class, "getUser"])->addRouteMiddleware(AdminRequiredMiddleware::class);

    $app->get("/admin/users/{user}/receipt/{receipt}", [ReceiptController::class, "download"])->addRouteMiddleware(AdminRequiredMiddleware::class);

    // route non trovata
    $app->setErrorHandler([ErrorController::class, "notFound"]);
}
