<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, ReceiptService};

class ReceiptController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private ReceiptService $receiptService
    ) {
    }

    public function getUpload(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params);

        if (!$transaction) {
            redirectTo("/");
        }

        echo $this->view->render("receipts/create.php");
    }

    public function upload(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params);

        if (!$transaction) {
            redirectTo("/");
        }

        $receiptFile = $_FILES['receipt'] ?? null;

        $this->receiptService->validate($receiptFile);

        $this->receiptService->upload($receiptFile, $transaction['id']);

        redirectTo("/");
    }

    public function download(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params);

        // verifica se la transazione è presente nel database
        if (empty($transaction)) {
            redirectTo("/");
        }

        $receipt = $this->receiptService->getReceipt($params['receipt']);

        // verifica se ci sono file relativi alla transazione
        if (empty($receipt)) {
            redirectTo("/");
        }

        if ($receipt['transaction_id'] !== $transaction['id']) {
            redirectTo("/");
        }

        $this->receiptService->read($receipt);
    }

    public function delete(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params);

        // verifica se la transazione è presente nel database
        if (empty($transaction)) {
            redirectTo("/");
        }

        $receipt = $this->receiptService->getReceipt($params['receipt']);

        // verifica se ci sono file relativi alla transazione
        if (empty($receipt)) {
            redirectTo("/");
        }

        // cancella il file nel database
        $this->receiptService->delete($receipt);

        redirectTo("/");
    }
}
