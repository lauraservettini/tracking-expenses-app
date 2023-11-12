<?php

declare(strict_types=1);

require __dir__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Config\Paths;
use Dotenv\Dotenv;

use function App\Config\{registerRoutes, registerMiddleware};

// carica .env
$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$app = new App(Paths::SOURCE . "/App/container-definitions.php");

registerRoutes($app);

registerMiddleware($app);

return $app;
