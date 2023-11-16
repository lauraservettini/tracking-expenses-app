<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class AdminService
{
    public function __construct(private Database $db)
    {
    }

    public function getAllTransactions(int $length, int $offset)
    {
        // fa un escape della stringa ricercata nel search form
        $searchTerm = addcslashes($_GET['s'] ?? "", "%_'");

        // parametri da sostituire nella query
        $params = [
            "search_term" => "%{$searchTerm}%"
        ];

        $transactions = $this->db->query(
            "SELECT 
                t.id as transaction_id, 
                t.amount as amount, 
                t.description as description, 
                u.id as user_id,
                u.email as user_email,
                DATE_FORMAT(t.date, '%Y-%m-%d') as formatted_date
                FROM transactions as t 
                LEFT JOIN users as u ON t.user_id = u.id
            WHERE t.description LIKE :search_term;
            LIMIT {$length} OFFSET {$offset};",
            $params
        )->findAll();

        $transactions = array_map(function (array $transaction) {
            $transaction['receipts'] = $this->db->query(
                "SELECT * FROM receipts
                WHERE transaction_id = :transaction_id ;",
                [
                    "transaction_id" => $transaction['transaction_id']
                ]
            )->findAll();

            return $transaction;
        }, $transactions);

        $transactionCount = $this->db->query(
            "SELECT COUNT(*)
            FROM transactions 
            WHERE description LIKE :search_term;",
            $params
        )->count();

        return [$transactions, $transactionCount];
    }

    public function getUserTransactions(int $length, int $offset, int $userId)
    {
        // fa un escape della stringa ricercata nel search form
        $searchTerm = addcslashes($_GET['s'] ?? "", "%_'");

        // parametri da sostituire nella query
        $params = [
            "user_id" => $userId,
            "search_term" => "%{$searchTerm}%"
        ];

        $transactions = $this->db->query(
            "SELECT 
            t.id as transaction_id, 
            t.amount as amount, 
            t.description as description, 
            u.id as user_id,
            u.email as user_email,
            DATE_FORMAT(date, '%Y-%m-%d') as formatted_date
            FROM transactions as t
            RIGHT JOIN users as u ON t.user_id = u.id
            WHERE t.user_id = :user_id
            AND description LIKE :search_term 
            LIMIT {$length} OFFSET {$offset};",
            $params
        )->findAll();

        $transactions = array_map(function (array $transaction) {
            $transaction['receipts'] = $this->db->query(
                "SELECT * FROM receipts
                WHERE transaction_id = :transaction_id ;",
                [
                    "transaction_id" => $transaction['transaction_id']
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

    public function getAllUsers(int $length, int $offset,)
    {
        // fa un escape della stringa ricercata nel search form
        $searchTerm = addcslashes($_GET['s'] ?? "", "%_'");

        $params = [
            "search_term" => "%{$searchTerm}%"
        ];

        $users = $this->db->query(
            "SELECT * FROM users
            WHERE email LIKE :search_term,
            LIMIT {$length} OFFSET {$offset};",
            $params
        )->findAll();

        $usersCount = $this->db->query(
            "SELECT COUNT(*)
            FROM users 
            WHERE email LIKE :search_term;",
            $params
        )->count();

        return [$users, $usersCount];
    }
}
