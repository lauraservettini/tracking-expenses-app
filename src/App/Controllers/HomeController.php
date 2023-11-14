<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\TransactionService;
use Transliterator;

class HomeController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService
    ) {
    }

    public function home()
    {
        $transactions = $this->transactionService->getUserTransactions();

        echo $this->view->render(
            "/index.php",
            [
                "transactions" => $transactions
            ]
        );
    }
}
