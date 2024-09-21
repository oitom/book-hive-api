<?php
namespace App\Infrastructure\Repository;

use App\Domain\Repositories\BookRepositoryInterface;
use App\Domain\Entity\Book;
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

  public function save(Book $book): bool
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

  public function find(): array
  {
    $stmt = $this->connection->prepare('
        SELECT b.*, 
              GROUP_CONCAT(DISTINCT a.nome) AS autores, 
              GROUP_CONCAT(DISTINCT s.descricao) AS assuntos
        FROM livros b
        LEFT JOIN autores a ON a.livro_id = b.id
        LEFT JOIN assuntos s ON s.livro_id = b.id
        WHERE b.deletedAt IS NULL
        GROUP BY b.id
    ');
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
  }

  public function update(int $bookId, Book $book): bool
  {
    try {
      $this->connection->beginTransaction();

      // Atualizar os dados do livro na tabela livros
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

      // Atualizar autores e assuntos
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

  public function delete(int $id, Book $book): bool
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

  private function insertBook(Book $book): int
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
