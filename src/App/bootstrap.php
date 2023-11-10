<?php

declare(strict_types=1);

require __dir__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Controllers\HomeController;

$app = new App();

$app->get("/", [HomeController::class, "home"]);

return $app;