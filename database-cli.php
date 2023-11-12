<?php

include __DIR__ . "/src/Framework/database.php";

use Framework\Database;

$db = new Database("mysql", [
    "host" => "localhost",
    "post" => 3306,
    "dbname" => "expense_tracking_app"
], "root", "");

echo "Connected to the database";
