<?php
class JWTCodec{
  public function encode(array $payload):string{
    $header = json_encode([
      "typ" => "JWT",
      "alg" => "HS256"
    ]);
    $header = $this->base64UrlEncode($header);
    $payload = $this->base64UrlEncode(json_encode($payload));
    $signature = hash_hmac("sha256",
    $header . "." . $payload,
    "5A7134743777217A25432A462D4A614E645266556A586E3272357538782F413F");
    return $header . "." . $payload . "." . $signature;
  }
  private function base64UrlEncode(string $text):string{
    return str_replace(
      ["+","/","="],
      ["-","_",""],
      base64_encode($text)
    );
  }
}
?>