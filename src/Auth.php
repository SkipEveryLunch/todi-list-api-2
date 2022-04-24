<?php
class Auth{
  private int $user_id;
  private UserGateway $gateway;
  private JWTCodec $jwtcodec;
  public function __construct(UserGateway $gateway,JWTCodec $jwtcodec){
    $this->gateway = $gateway;
    $this->jwtcodec = $jwtcodec;
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
  public function authenticateAccessToken():bool{
    if(!preg_match("/^Bearer\s+(.*)$/",$_SERVER["HTTP_AUTHORIZATION"],$matches)){
      http_response_code(400);
      echo json_encode([
        "message"=>"incomplete authorization header"
      ]);
      return false;
    }
    try{
      $data = $this->jwtcodec->decode($matches[1]);
    }catch(InvalidSignatureException $e){
      http_response_code(401);
      echo json_encode([
        "message"=>"Invalid signature"
      ]);
      return false;
    }catch(TokenExpiredException $e){
      http_response_code(401);
      echo json_encode([
        "message"=>"Token Expired"
      ]);
      return false;
    }catch(Exception $e){
      http_response_code(400);
      echo json_encode([
        "message"=>$e->getMessage()
      ]);
      return false;
    }
    $this->user_id = $data["sub"];
    return true;
  }
}
?>