<?php

include __DIR__ . "/src/Framework/Database.php";

use Framework\Database;

$db = new Database("mysql", [
    "host" => "localhost",
    "post" => 3306,
    "dbname" => "expense_tracking_app"
], "root", "");

$sqlFile = file_get_contents("./database.sql");

$db->query($sqlFile);

// try {
//     $db->connection->beginTransaction();

//     $search = "Value";

//     // creare un "Prepared Statement" per evitare la SQL Injection
//     // può essere usata o la key dell'array associativo o ?
//     // $query = "SELECT * FROM products WHERE name=:name";

//     // $stmt = $db->connection->prepare($query);

//     // bindValue associa i parametri senza eseguire lo statement
//     $stmt->bindValue("name", 'Gloves', PDO::PARAM_STR);

//     $stmt->execute();ù

//     $db->connection->commit();
// } catch (Exception $error) {
//     // verifica se la transaction è attiva
//     if ($db->connection->inTransaction()) {
//         $db->connection->rollBack();
//     }
//     echo "Transaction Failed";
// }
