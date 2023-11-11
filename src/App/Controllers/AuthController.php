<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\ValidatorService;
// use App\Config\Paths;

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService
    ) {
    }

    public function register()
    {
        echo $this->view->render("/auth/register.php", [
            "title" => "Register page",

        ]);
    }

    public function registerForm()
    {
        $this->validatorService->validateRegister($_POST);
    }
}
