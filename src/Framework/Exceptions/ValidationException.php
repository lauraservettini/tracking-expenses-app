<?php

declare(strict_types=1);

namespace Framework\Exceptions;

use RuntimeException;

// crea una classe per validare i campi del form
class ValidationException extends RuntimeException
{
    public array $errors;
    public function __construct(array $errors, int $code = 422)
    {
        $this->errors = $errors;
        parent::__construct(code: $code);
    }
}
