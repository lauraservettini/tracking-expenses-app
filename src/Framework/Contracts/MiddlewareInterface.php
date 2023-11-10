<?php

declare(strict_types=1);

namespace Framework\Contracts;

interface MiddlewareInterface
{
    // processa la richiesta, chiamata prima che il controller riceva la richiesta
    public function process(callable $next);
}
