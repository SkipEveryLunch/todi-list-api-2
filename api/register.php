<?php

  declare(strict_types=1);

  require __DIR__ . "/bootstrap.php";

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
  header("Access-Control-Allow-Headers *");

  if($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(405);
    header("Allow: POST");
    exit;
  }

  $data = (array) json_decode(file_get_contents("php://input"), true);
  if(
    !array_key_exists("name",$data)||
    !array_key_exists("username",$data)||
    !array_key_exists("password",$data)
  ){
    http_response_code(400);
    echo json_encode([
      "message" => "missing register credentials"
    ]);
    exit;
  }
  $database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

  $conn = $database->getConnection();

  $sql = "INSERT INTO user (name, username, password_hash)
  VALUES (:name, :username, :password_hash)";
  
  $stmt = $conn->prepare($sql);

  $password_hash = password_hash($data["password"], PASSWORD_DEFAULT);

  $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
  $stmt->bindValue(":username", $data["username"], PDO::PARAM_STR);
  $stmt->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);

  $stmt->execute();

  echo "Successfully registered";
  exit;
?>
