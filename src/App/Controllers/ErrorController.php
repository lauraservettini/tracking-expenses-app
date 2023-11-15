<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class ErrorController
{
    public function __construct(private TemplateEngine $view)
    {
    }

    public function notFound()
    {
        echo $this->view->render("errors/404.php");
    }
}
