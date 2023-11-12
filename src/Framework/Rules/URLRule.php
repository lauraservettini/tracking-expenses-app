<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class URLRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        $URLisValid = (bool)filter_var($data[$field], FILTER_VALIDATE_URL);

        return $URLisValid;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Invalid URL.";
    }
}
