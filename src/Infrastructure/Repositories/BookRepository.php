<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\BookRepositoryInterface;
use App\Domain\Entities\BookEntity;
use App\Infrastructure\Database\PDOConnection;
use PDO;

class BookRepository implements BookRepositoryInterface
{
  private PDO $connection;
  private AuthorRepository $autorRepository;
  private SubjectRepository $assuntoRepository;

  public function __construct()
  {
    $pdoConnection = new PDOConnection();
    $this->connection = $pdoConnection->getConnection();
    
    $this->autorRepository = new AuthorRepository($this->connection);
    $this->assuntoRepository = new SubjectRepository($this->connection);
  }

  public function save(BookEntity $book): bool
  {
    try {
      $this->connection->beginTransaction();

      $bookId = $this->insertBook($book);
      $this->autorRepository->saveAll($book->getAutores(), $bookId);
      $this->assuntoRepository->saveAll($book->getAssuntos(), $bookId);

      $this->connection->commit();
      return true;
    } catch (\Exception $e) {
      $this->connection->rollBack();
      throw new \RuntimeException('Error saving book: ' . $e->getMessage());
    }
  }

  public function findOne(int $id): array | null
  {
    $stmt = $this->connection->prepare( 'SELECT b.*, 
          GROUP_CONCAT(DISTINCT a.nome) AS autores, 
          GROUP_CONCAT(DISTINCT s.descricao) AS assuntos
      FROM livros b
      LEFT JOIN autores a ON a.livro_id = b.id
      LEFT JOIN assuntos s ON s.livro_id = b.id
      WHERE b.id = :id AND b.deletedAt IS NULL
      GROUP BY b.id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function find(string $search, int $pageSize, int $offset): array
  {
    $searchQuery = '';
    if ($search) {
      $searchQuery = 'AND (b.titulo LIKE :search OR a.nome LIKE :search OR s.descricao LIKE :search)';
    }

    $countStmt = $this->connection->prepare('
      SELECT COUNT(*) as total
      FROM (
          SELECT b.id
          FROM livros b
          LEFT JOIN autores a ON a.livro_id = b.id
          LEFT JOIN assuntos s ON s.livro_id = b.id
          WHERE b.deletedAt IS NULL ' . $searchQuery . '
          GROUP BY b.id
      ) as subquery'
    );

    if ($search) {
      $countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

    $totalPages = ceil($totalRecords / $pageSize);

    $stmt = $this->connection->prepare('
      SELECT b.*, 
            GROUP_CONCAT(DISTINCT a.nome) AS autores, 
            GROUP_CONCAT(DISTINCT s.descricao) AS assuntos
      FROM livros b
      LEFT JOIN autores a ON a.livro_id = b.id
      LEFT JOIN assuntos s ON s.livro_id = b.id
      WHERE b.deletedAt IS NULL ' . $searchQuery . '
      GROUP BY b.id
      LIMIT :limit OFFSET :offset
    ');

    if ($search) {
      $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $pageSize, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    return [
      'books' => $books,
      'pagination' => [
        'count' => $totalRecords,
        'countPages' => $totalPages,
        'currentPage' => ($offset / $pageSize) + 1
      ]
    ];
  }
  
  public function update(int $bookId, BookEntity $book): bool
  {
    try {
      $this->connection->beginTransaction();

      $stmt = $this->connection->prepare(
        'UPDATE livros 
          SET titulo = :titulo, editora = :editora, edicao = :edicao, 
            anoPublicacao = :anoPublicacao, preco = :preco, updatedAt = :updatedAt 
          WHERE id = :id'
      );

      $stmt->execute([
        ':titulo' => $book->getTitulo(),
        ':editora' => $book->getEditora(),
        ':edicao' => $book->getEdicao(),
        ':anoPublicacao' => $book->getAnoPublicacao(),
        ':preco' => $book->getPreco(),
        ':updatedAt' => $book->getUpdatedAt(),
        ':id' => $bookId
      ]);

    $this->autorRepository->deleteAllByBookId($bookId);
      $this->autorRepository->saveAll($book->getAutores(), $bookId);

      $this->assuntoRepository->deleteAllByBookId($bookId);
      $this->assuntoRepository->saveAll($book->getAssuntos(), $bookId);

      $this->connection->commit();
      return true;
    } catch (\Exception $e) {
      $this->connection->rollBack();
      throw new \RuntimeException('Error updating book: ' . $e->getMessage());
    }
  }

  public function delete(int $id, BookEntity $book): bool
  {
    try {
      $this->connection->beginTransaction();

      $stmt = $this->connection->prepare('UPDATE livros SET deletedAt = :deletedAt WHERE id = :id');
      $stmt->execute([
        ':deletedAt' => $book->getDeletedAt(),
        ':id' => $id
      ]);

      $this->connection->commit();
      return true;
    } catch (\Exception $e) {
      $this->connection->rollBack();
      throw new \RuntimeException('Error deleting book: ' . $e->getMessage());
    }
  }

  private function insertBook(BookEntity $book): int
  {
    $stmt = $this->connection->prepare(
      'INSERT INTO livros (titulo, editora, edicao, anoPublicacao, preco, createdAt) 
        VALUES (:titulo, :editora, :edicao, :anoPublicacao, :preco, :createdAt)'
    );
    $stmt->execute([
      ':titulo' => $book->getTitulo(),
      ':editora' => $book->getEditora(),
      ':edicao' => $book->getEdicao(),
      ':anoPublicacao' => $book->getAnoPublicacao(),
      ':preco' => $book->getPreco(),
      ':createdAt' => $book->getCreatedAt(),
    ]);

    return $this->connection->lastInsertId();
  }
}
