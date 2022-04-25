<?php
$payload = [
  "sub" => $user["id"],
  "name" => $user["name"],
  "exp" => time() + $_ENV["TOKEN_EXPIRATION_TIME"]
];
$access_token = $codec->encode($payload);
$refresh_token = $codec->encode([
  "sub"=>$user["id"],
  "exp"=>time()+432000
]);
echo json_encode([
  "access_token"=>$access_token,
  "refresh_token"=>$refresh_token
]);
?>