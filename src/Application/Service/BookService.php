<?php

namespace App\Application\Service;

use App\Infrastructure\Repository\BookRepository;
use App\Domain\Entity\Book;

class BookService
{
  private BookRepository $bookRepository;

  public function __construct(BookRepository $bookRepository)
  {
    $this->bookRepository = $bookRepository;
  }

  public function create(array $validatedData)
  {
    // Cria a entidade Book com os dados validados
    $book = new Book(
      $validatedData['titulo'],
      $validatedData['editora'],
      $validatedData['edicao'],
      $validatedData['anoPublicacao'],
      $validatedData['preco'],
      $validatedData['autor'],
      $validatedData['assunto']
    );

    // Salva no repositório
    return $this->bookRepository->save($book);
  }
}
