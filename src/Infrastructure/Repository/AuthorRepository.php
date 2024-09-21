<?php
namespace App\Infrastructure\Repository;

use PDO;

class AuthorRepository
{
  private PDO $connection;

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  public function saveAll(array $autores, int $bookId): void
  {
    $stmt = $this->connection->prepare(
      'INSERT INTO autores (livro_id, nome) VALUES (:livro_id, :nome)'
    );

    foreach ($autores as $autor) {
      $stmt->execute([
        ':livro_id' => $bookId,
        ':nome' => $autor->getNome()
      ]);
    }
  }
}
