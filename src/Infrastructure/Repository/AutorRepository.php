<?php
namespace App\Infrastructure\Repository;

use App\Domain\Entity\Autor;
use PDO;

class AutorRepository
{
  private PDO $connection;

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  public function saveAll(array $autores, int $bookId): void
  {
    $stmt = $this->connection->prepare(
      'INSERT INTO autores (book_id, nome) VALUES (:book_id, :nome)'
    );

    foreach ($autores as $autor) {
      $stmt->execute([
        ':book_id' => $bookId,
        ':nome' => $autor->getNome()
      ]);
    }
  }
}
