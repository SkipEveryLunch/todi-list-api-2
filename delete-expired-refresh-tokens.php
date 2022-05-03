<?php

declare(strict_types=1);

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$database = new Database($_ENV["DB_HOST"], getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
$refresh_token_gateway = new RefreshTokenGateway($database,getenv("SEACRET_KEY"));

echo $refresh_token_gateway->deleteExpired() . "\n";
?>


