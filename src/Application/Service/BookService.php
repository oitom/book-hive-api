<?php

namespace App\Application\Service;

use App\Infrastructure\Repository\BookRepository;
use App\Domain\Entity\Book;
use App\Domain\Entity\Autor;
use App\Domain\Entity\Assunto;

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

    $autores = array_map(function($autorData) {
      return new Autor($autorData['nome']);
    }, $validatedData['autor']);

    $assuntos = array_map(function($assuntoData) {
      return new Assunto($assuntoData['descricao']);
    }, $validatedData['assunto']);

    $book = new Book(
      $validatedData['titulo'],
      $validatedData['editora'],
      $validatedData['edicao'],
      $validatedData['anoPublicacao'],
      $validatedData['preco'],
      $autores,
      $assuntos
    );

    // Salva no repositório
    return $this->bookRepository->save($book);
  }
}
