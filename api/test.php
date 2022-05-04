<?php
  declare(strict_types=1);
  require __DIR__ . '/bootstrap.php';

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');

  echo json_encode("this is a test");
?>