<?php

declare(strict_types=1);

namespace Framework\Contracts;

interface RuleInterface
{
    public function validate(array $data, string $field, array $params): bool;

    // se la validazione fallisce messaggio di errore
    public function getMessage(array $data, string $field, array $params): string;
}
