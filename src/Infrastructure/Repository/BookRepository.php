<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Book;
use App\Infrastructure\Database\PDOConnection;
use PDO;

class BookRepository
{
  private PDO $connection;

  public function __construct()
  {
    $pdoConnection = new PDOConnection();
    $this->connection = $pdoConnection->getConnection();
  }

  public function save(Book $book): bool
  {
    try {
      $this->connection->beginTransaction();

      // Inserir o livro na tabela 'books'
      $stmt = $this->connection->prepare(
        'INSERT INTO books (titulo, editora, edicao, anoPublicacao, preco) 
          VALUES (:titulo, :editora, :edicao, :anoPublicacao, :preco)'
      );
      $stmt->execute([
        ':titulo' => $book->getTitulo(),
        ':editora' => $book->getEditora(),
        ':edicao' => $book->getEdicao(),
        ':anoPublicacao' => $book->getAnoPublicacao(),
        ':preco' => $book->getPreco()
      ]);

      // Obter o ID do livro recém-criado
      $bookId = $this->connection->lastInsertId();

      // Inserir os autores na tabela 'autores'
      foreach ($book->getAutor() as $autor) {
        $stmtAutor = $this->connection->prepare(
          'INSERT INTO autores (book_id, nome) 
            VALUES (:book_id, :nome)'
        );
        $stmtAutor->execute([
          ':book_id' => $bookId,
          ':nome' => $autor['nome']
        ]);
      }

      // Inserir os assuntos na tabela 'assuntos'
      foreach ($book->getAssunto() as $assunto) {
        $stmtAssunto = $this->connection->prepare(
          'INSERT INTO assuntos (book_id, descricao) 
            VALUES (:book_id, :descricao)'
        );
        $stmtAssunto->execute([
          ':book_id' => $bookId,
          ':descricao' => $assunto['descricao']
        ]);
      }

      // Confirmar transação
      $this->connection->commit();
      return true;
    } catch (\Exception $e) {
      // Em caso de erro, reverter a transação
      $this->connection->rollBack();
      throw new \RuntimeException('Error saving book: ' . $e->getMessage());
    }
  }
}
