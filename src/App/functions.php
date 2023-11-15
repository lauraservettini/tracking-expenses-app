<?php

declare(strict_types=1);

use Framework\Http;

function dd(mixed $value)
{
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    die();
}

function escape(mixed $value): string
{
    return htmlspecialchars((string) $value);
}

function redirectTo(string $path)
{
    header("Location: {$path}");
    http_response_code(Http::REDIRECT_STATUS_CODE);
    exit;
}
