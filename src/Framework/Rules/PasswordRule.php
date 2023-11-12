<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class PasswordRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        // Verifica minLength 8
        // Verifica presenza di almeno un carattere maiuscolo e un carattere minuscolo, di almeno un numero e di un carattere speciale
        $passwordRegexRule = "~^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$~";
        return (bool) preg_match($passwordRegexRule, $data[$field]);
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "The password must have a lenght of 8 characters. It must contain at least a special character, a number, a lowercase and an uppercase letter.";
    }
}
