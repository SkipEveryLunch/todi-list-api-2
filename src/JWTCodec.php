<?php
class JWTCodec{
  public $key;
  public function __construct(string $key){
    $this->key = $key;
  }
  public function encode(array $payload):string{
    $header = json_encode([
      "typ" => "JWT",
      "alg" => "HS256"
    ]);
    $header = $this->base64UrlEncode($header);
    $payload = json_encode($payload);
    $payload = $this->base64UrlEncode($payload);
    $signature = hash_hmac("sha256",
    $header . "." . $payload,
    $this->key,true);
    $signature = $this->base64urlEncode($signature);
    return $header . "." . $payload . "." . $signature;
  }
  public function decode(string $token){
    if(preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",$token,$matches)!==1){
      throw new InvalidArgumentException("invalid token format");
    }
    $signature = hash_hmac("sha256",
    $matches["header"] . "." . $matches["payload"],
    $this->key,true);
    $signature_from_token = $this->base64UrlDecode($matches["signature"]);
    if(!hash_equals($signature,$signature_from_token)){
      throw new InvalidSignatureException("signature doesn't match");
    };
    $payload = json_decode($this->base64UrlDecode($matches["payload"]),true);
    if($payload["exp"]<time()){
      throw new TokenExpiredException;
    }else
    return $payload;
  }
  private function base64UrlEncode(string $text):string{
    return str_replace(
      ["+","/","="],
      ["-","_",""],
      base64_encode($text)
    );
  }
  private function base64UrlDecode(string $text):string{
    return base64_decode(str_replace(
      ["-","_"],
      ["+","/"],
      $text
    ));
  }
}
?>