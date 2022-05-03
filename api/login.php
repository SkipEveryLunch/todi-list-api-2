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
  if(!array_key_exists("username",$data)||
  !array_key_exists("password",$data)){
    http_response_code(400);
    echo json_encode([
      "message" => "missing login credentials"
    ]);
    exit;
  }
  $database = new Database($_ENV["DB_HOST"], getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

  $user_gateway = new UserGateway($database);
  $user = $user_gateway->getByUsername($data["username"]);
  if($user == false){
    http_response_code(401);
    echo json_encode([
      "message" => "invalid authentication"
    ]);
    exit;
  }
  //password_veryfy($plain, $hash):bool
  if(!password_verify($data["password"],$user["password_hash"])){
    http_response_code(401);
    echo json_encode([
      "message" => "invalid authentication"
    ]);
    exit;
  }
  $codec = new JWTCodec(getenv("SEACRET_KEY"));
  require __DIR__ . "/tokens.php";

  $refresh_token_gateway = new RefreshTokenGateway($database,getenv("SEACRET_KEY"));
  $refresh_token_gateway->create($refresh_token,$refresh_token_expiry);
?>
