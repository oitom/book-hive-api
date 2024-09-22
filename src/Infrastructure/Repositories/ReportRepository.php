<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ReportRepositoryInterface;
use App\Infrastructure\Database\PDOConnection;
use PDO;

class ReportRepository implements ReportRepositoryInterface
{
  private PDO $connection;

  public function __construct()
  {
    $pdoConnection = new PDOConnection();
    $this->connection = $pdoConnection->getConnection();
  }

  public function setConnection(PDO $connection): void
  {
    $this->connection = $connection;
  }
  
  public function find(string $search, int $pageSize, int $offset): array
  {
    $searchQuery = '';
    if ($search) {
      $searchQuery = 'AND (titulo LIKE :search OR autores LIKE :search OR assuntos LIKE :search)';
    }

    $countStmt = $this->connection->prepare('
      SELECT COUNT(*) as total
      FROM relatorio_livros
      WHERE ativo = 1 ' . $searchQuery
    );

    if ($search) {
      $countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    $countStmt->execute();
    $stmt = $this->connection->prepare('
        SELECT *
        FROM relatorio_livros
        WHERE ativo = 1 ' . $searchQuery . '
        LIMIT :limit OFFSET :offset
    ');

    if ($search) {
      $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', $pageSize, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $books =  $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    
    return ['books' => $books];
  }
}
