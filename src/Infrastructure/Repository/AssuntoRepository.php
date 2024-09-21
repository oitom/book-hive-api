<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Assunto;
use PDO;

class AssuntoRepository
{
  private PDO $connection;

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  public function saveAll(array $assuntos, int $bookId): void
  {
    $stmt = $this->connection->prepare(
      'INSERT INTO assuntos (livro_id, descricao) VALUES (:livro_id, :descricao)'
    );

    foreach ($assuntos as $assunto) {
      $stmt->execute([
        ':livro_id' => $bookId,
        ':descricao' => $assunto->getDescricao()
      ]);
    }
  }
}
