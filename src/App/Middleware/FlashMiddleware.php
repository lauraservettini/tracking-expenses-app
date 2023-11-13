<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class FlashMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TemplateEngine $view
    ) {
    }

    public function process(callable $next)
    {
        // aggiunge i dati degli errori del form alle variabili globali $globalTemplateData del custom TemplateEngine
        // riporta il valore di $globalTemplateData["errors"] a [] se non ci sono errori, anche nel caso in cui erano stati salvati precedentemente
        $this->view->addGlobal("errors", $_SESSION["errors"] ?? []);


        // distrugge i messaggi di errore una volta passati
        unset($_SESSION["errors"]);

        // aggiunge i dati immessi nel form se compilato
        $this->view->addGlobal("oldFormData", $_SESSION["oldFormData"] ?? []);

        // distrugge i messaggi di errore una volta passati
        unset($_SESSION["oldFormData"]);

        // crea la variabile globale isAuth
        if (empty($_SESSION['user'])) {
            $this->view->addGlobal("auth", ["isAuth" => (bool) false]);
        } else {
            $this->view->addGlobal("auth", ["isAuth" => (bool) true]);
        }

        $next();
    }
}
