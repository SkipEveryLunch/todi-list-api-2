<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

$resource = $parts[3];

$id = $parts[4] ?? null;

if ($resource != "tasks") {
    
    http_response_code(404);
    exit;
}

$database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

$user_gateway = new UserGateway($database);

// var_dump($_SERVER["HTTP_AUTHORIZATION"]);
// $headers = apache_request_headers();
// echo $headers["Authorization"];
// exit;

$auth = new Auth($user_gateway);

if($auth->authenticateAccessToken()==false){
    exit;
}

$user_id = $auth->getUserId();

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway,$user_id);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);

?>








