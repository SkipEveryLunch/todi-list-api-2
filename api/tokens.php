<?php
$payload = [
  'sub' => $user['id'],
  'name' => $user['name'],
  'exp' => time() + $_ENV['TOKEN_EXPIRATION_TIME']
];
$access_token = $codec->encode($payload);
$refresh_token_expiry = time()+432000;
$refresh_token = $codec->encode([
  'sub'=>$user['id'],
  'exp'=>$refresh_token_expiry
]);
// echo json_encode([
//   'access_token'=>$access_token,
//   'refresh_token'=>$refresh_token
// ]);
echo json_encode("hello");
?>