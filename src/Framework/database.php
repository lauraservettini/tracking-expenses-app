<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException, PDOStatement;

class Database
{
    private PDO $connection;
    private PDOStatement $stmt;

    public function __construct(string $driver, array $config, string $username, string $password)
    {
        $config = http_build_query(data: $config, arg_separator: ";");

        // crea la string DSN
        $dsn = "{$driver}:{$config}";

        // connette al database tramite PDO (PHP Data Object)
        // con l'opzione array ritorna un array associativo
        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Unable to connect to database");
        }
    }

    public function query(string $query, $params = []): Database
    {
        // ritorna uno statement(PDO Statement)
        $this->stmt = $this->connection->prepare($query);

        $this->stmt->execute($params);

        return $this;
    }

    public function count()
    {
        return $this->stmt->fetchColumn();
    }

    public function find()
    {
        return $this->stmt->fetch();
    }

    public function findAll()
    {
        return $this->stmt->fetchAll();
    }

    public function id()
    {
        // ritorna l'id dell'ultimo record inserito nel database
        return $this->connection->lastInsertId();
    }
}
