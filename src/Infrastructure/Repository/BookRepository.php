<?php
namespace App\Infrastructure\Repository;

use App\Domain\Entity\Book;
use App\Infrastructure\Database\PDOConnection;
use PDO;

class BookRepository
{
  private PDO $connection;
  private AutorRepository $autorRepository;
  private AssuntoRepository $assuntoRepository;

  public function __construct()
  {
    $pdoConnection = new PDOConnection();
    $this->connection = $pdoConnection->getConnection();
    
    $this->autorRepository = new AutorRepository($this->connection);
    $this->assuntoRepository = new AssuntoRepository($this->connection);
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
      // Reverter transação em caso de erro
      $this->connection->rollBack();
      throw new \RuntimeException('Error saving book: ' . $e->getMessage());
    }
  }

  private function insertBook(Book $book): int
  {
    $stmt = $this->connection->prepare(
        'INSERT INTO books (titulo, editora, edicao, anoPublicacao, preco, ativo, createdAt) 
          VALUES (:titulo, :editora, :edicao, :anoPublicacao, :preco, :ativo, :createdAt)'
    );
    $stmt->execute([
      ':titulo' => $book->getTitulo(),
      ':editora' => $book->getEditora(),
      ':edicao' => $book->getEdicao(),
      ':anoPublicacao' => $book->getAnoPublicacao(),
      ':preco' => $book->getPreco(),
      ':ativo' => $book->getAtivo(),
      ':createdAt' => $book->getCreatedAt(),
    ]);

    return $this->connection->lastInsertId();
  }
}
