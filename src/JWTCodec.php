<?php
class JWTCodec{
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
    "5A7134743777217A25432A462D4A614E645266556A586E3272357538782F413F",true);
    $signature = $this->base64urlEncode($signature);
    return $header . "." . $payload . "." . $signature;
  }
  public function decode(string $token){
    if(preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",$token,$matches)!==1){
      throw new InvalidArgumentException("invalid token format");
    }
    $signature = hash_hmac("sha256",
    $matches["header"] . "." . $matches["payload"],
    "5A7134743777217A25432A462D4A614E645266556A586E3272357538782F413F",true);
    $signature_from_token = $this->base64UrlDecode($matches["signature"]);
    if(!hash_equals($signature,$signature_from_token)){
      throw new Exception("signature doesn't match");
    };
    $payload = json_decode($this->base64UrlDecode($matches["payload"]),true);
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