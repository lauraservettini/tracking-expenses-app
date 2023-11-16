<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db)
    {
    }

    public function create(array $formData)
    {
        $formattedDate = "{$formData['date']} 00:00:00";

        $this->db->query(
            "INSERT INTO transactions(user_id, description, amount, date)
            VALUES (:user_id, :description, :amount, :date);",
            [
                "user_id" => $_SESSION['user'],
                "description" => $formData['description'],
                "amount" => $formData['amount'],
                "date" => $formattedDate
            ]
        );
    }

    public function getUserTransactions(int $length, int $offset)
    {
        // fa un escape della stringa ricercata nel search form
        $searchTerm = addcslashes($_GET['s'] ?? "", "%_'");

        // parametri da sostituire nella query
        $params = [
            "user_id" => $_SESSION['user'],
            "search_term" => "%{$searchTerm}%"
        ];

        $transactions = $this->db->query(
            "SELECT * , DATE_FORMAT(date, '%Y-%m-%d') as formatted_date
            FROM transactions 
            WHERE user_id = :user_id
            AND description LIKE :search_term 
            LIMIT {$length} OFFSET {$offset};",
            $params
        )->findAll();

        $transactions = array_map(function (array $transaction) {
            $transaction['receipts'] = $this->db->query(
                "SELECT * FROM receipts
                WHERE transaction_id = :transaction_id ;",
                [
                    "transaction_id" => $transaction['id']
                ]
            )->findAll();

            return $transaction;
        }, $transactions);

        $transactionCount = $this->db->query(
            "SELECT COUNT(*)
            FROM transactions 
            WHERE user_id = :user_id
            AND description LIKE :search_term;",
            $params
        )->count();
        return [$transactions, $transactionCount];
    }

    public function getUserTransaction(array $params)
    {
        if (!empty($_SESSION['isAdmin'])) {
            $receiptId = $params['receipt'];
            $userId = $params['user'];
            $id = $this->db->query(
                "SELECT transaction_id as id FROM receipts 
                WHERE id = :id;",
                [
                    "id" => $receiptId
                ]
            )->find();
            $id = $id['id'];
        } else {
            $userId = $_SESSION['user'];
            $id = $params['transaction'];
        }

        $params = [
            "id" => $id,
            "user_id" => $userId
        ];

        return $this->db->query(
            "SELECT * , DATE_FORMAT(date, '%Y-%m-%d') as formatted_date
            FROM transactions as t
            WHERE id = :id AND user_id = :user_id;",
            $params
        )->find();
    }

    public function update(array $formData, int $id)
    {
        $formattedDate = "{$formData['date']} 00:00:00";

        $this->db->query(
            "UPDATE transactions 
            SET description = :description,
                amount = :amount,
                date = :date
            WHERE id = :id AND user_id = :user_id;",
            [
                "description" => $formData['description'],
                "amount" => $formData['amount'],
                "date" => $formattedDate,
                "id" => $id,
                "user_id" => $_SESSION['user']
            ]
        );
    }

    public function delete(int $id)
    {
        $this->db->query(
            "DELETE FROM transactions 
            WHERE id= :id AND user_id = :user_id;",
            [
                "id" => $id,
                "user_id" => $_SESSION['user']
            ]
        );
    }
}
