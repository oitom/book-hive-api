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

       $this->checkIfTableExists('livros');
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['message' => 'Não foi possível estabelecer uma conexão']);
      return;
    }
  }

  public function getConnection() : ?PDO
  {
    return $this->connection ?? null;
  }

  private function checkIfTableExists(string $tableName): void
  {
    try {
      $query = $this->connection->prepare("SHOW TABLES LIKE :table");
      $query->execute(['table' => $tableName]);
      $result = $query->fetch();

      if (!$result) {
        // $this->runMigrations();
        throw new PDOException("Não foi possível realizar a operação. Tente novamente mais tarde!");
      }
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['message' => $e->getMessage()]);
      exit;
    }
  }
}
