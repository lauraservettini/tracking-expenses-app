<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, TransactionService};

class TransactionController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private TransactionService $transactionService
    ) {
    }

    public function getCreate()
    {
        echo $this->view->render("transactions/create.php");
    }

    public function create()
    {
        $this->validatorService->validateTransaction($_POST);

        $this->transactionService->create($_POST);

        redirectTo("/");
    }

    public function getEdit(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params);

        if (!$transaction) {
            redirectTo("/");
        }

        echo $this->view->render(
            "transactions/edit.php",
            [
                "transaction" => $transaction
            ]
        );
    }

    public function update(array $params)
    {
        // prima verifica se il record è salvato nel database
        $transaction = $this->transactionService->getUserTransaction($params);

        if (!$transaction) {
            redirectTo("/");
        }

        // verifica la validità dei campi
        $this->validatorService->validateTransaction($_POST);

        // poi fa l'update dei dati nel record
        $this->transactionService->update($_POST, $transaction['id']);

        redirectTo($_SERVER['HTTP_REFERER']);
    }

    public function delete(array $params)
    {
        $this->transactionService->delete((int)$params['transaction']);

        redirectTo("/");
    }
}
