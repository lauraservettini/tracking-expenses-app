<?php

declare(strict_types=1);

require __dir__ . "/../../vendor/autoload.php";

use Framework\App;

$app = new App();

$app->get("/");
$app->get("about/team");
$app->get("/about/team");
$app->get("/about/team/");

dd($app);

return $app;