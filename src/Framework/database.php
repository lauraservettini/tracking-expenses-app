<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException;

class Database
{
    public PDO $connection;

    public function __construct(string $driver, array $config, string $username, string $password)
    {
        $config = http_build_query(data: $config, arg_separator: ";");

        // crea la string DSN
        $dsn = "{$driver}:{$config}";

        // connette al database tramite PDO (PHP Data Object)
        try {
            $this->connection = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            die("Unable to connect to database");
        }
    }
}
