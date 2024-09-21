<?php

namespace App\Infrastructure\Database;


use PDO;
use PDOException;

class PDOConnection
{
  private PDO $connection;

  public function __construct()
  {
    $dsn = sprintf('mysql:host=%s;dbname=%s;port=%d;charset=utf8mb4',
      $_ENV['DB_HOST'],
      $_ENV['DB_DATABASE'],
      $_ENV['DB_PORT']
    );

    try {
      $this->connection = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      throw new \RuntimeException('Database connection error: ' . $e->getMessage());
    }
  }

  public function getConnection(): PDO
  {
    return $this->connection;
  }
}
