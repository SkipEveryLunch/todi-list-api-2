<?php
  class RefreshTokenGateway{
    private PDO $conn;
    private string $key;
    public function __construct(Database $db, string $key){
      $this->conn = $db->getConnection();
      $this->key = $key;
    }
    public function create(string $token, int $expiry){
      $hash = hash_hmac("sha256",$token,$this->key);
      $sql = "INSERT INTO refresh_token(token_hash,expires_at)
      VALUES (:token_hash, :expires_at)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":token_hash",$hash,PDO::PARAM_STR);
      $stmt->bindValue(":expires_at",$expiry,PDO::PARAM_STR);

      return $stmt->execute();
    }
    public function delete(string $token):int{
      $hash = hash_hmac("sha256",$token,$this->key);
      $sql = "DELETE FROM  refresh_token
      WHERE token_hash = :token_hash";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":token_hash",$hash,PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
  }
?>