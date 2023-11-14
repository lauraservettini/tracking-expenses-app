<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class DateRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        // date_parse_from_format("d/m/Y", $date); ritorna un array
        $date = date_parse_from_format($params[0], $data[$field]);

        return $date['error_count'] === 0 && $date['warning_count'] === 0;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "The date is not valid.";
    }
}
