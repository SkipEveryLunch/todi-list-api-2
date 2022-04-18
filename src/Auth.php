<?php
class Auth{
  private int $user_id;
  private UserGateway $gateway;
  public function __construct(UserGateway $gateway){
    $this->gateway = $gateway;
  }
  public function AuthenticateAPIKey():bool{

    if(empty($_SERVER["HTTP_X_API_KEY"])){
      echo json_encode([
        "message" => "missing API key"
      ]);
      return false;
    }

    $api_key = $_SERVER["HTTP_X_API_KEY"];

    $user = $this->gateway->getBYAPIKey($api_key);

    if( $user == false){
      echo json_encode([
        "message" => "invalid API key"
      ]);
      return false;
    }
    $this->user_id = $user["id"];
    return true;
  }
  public function getUserId():int{
    return $this->user_id;
  }
}
?>