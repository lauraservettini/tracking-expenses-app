<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService
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
        $this->userService->isEmailTaken($_POST['email']);

        $this->userService->create($_POST);
        //dopo la registrazione redirect alla home page
        redirectTo('/');
    }
}
