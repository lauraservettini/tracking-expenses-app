<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{RequiredRule, EmailRule, MinRule, InvalidRule, URLRule, PasswordRule, MatchRule};

class ValidatorService
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->add("required", new RequiredRule());
        $this->validator->add("email", new EmailRule());
        $this->validator->add("min", new MinRule());
        $this->validator->add("valid", new InvalidRule());
        $this->validator->add("URL", new URLRule());
        $this->validator->add("password", new PasswordRule());
        $this->validator->add("match", new MatchRule());
    }

    public function validateRegister(array $formData)
    {
        // controlla se tutti i campi rispettano le "rules" date
        $this->validator->validate($formData, [
            "email" => ["required", "email"],
            "age" => ["required", "min:18"], //aggiunge un parametro
            "country" => ["required", "valid:Italy,Great-Bretain,France"],
            "socialMediaURL" => ["required", "URL"],
            "password" => ["required", "password"],
            "confirmPassword" => ["required", "match:password"],
            "tos" => ["required"],
        ]);
    }
}
