<?php

namespace App\Application\Service;

use App\Domain\Repositories\BookRepositoryInterface;
use App\Domain\Entity\Book;
use App\Domain\Entity\Autor;
use App\Domain\Entity\Assunto;
use App\Domain\Commands\BookCreateCommand;
class BookService
{
  private BookRepositoryInterface $bookRepository;

  public function __construct(BookRepositoryInterface $bookRepository)
  {
    $this->bookRepository = $bookRepository;
  }

  public function create(BookCreateCommand $bookCreateCommand)
  {
    $autores = array_map(function($autor) {
      return new Autor($autor['nome']);
    }, $bookCreateCommand->autores);

    $assuntos = array_map(function($assunto) {
      return new Assunto($assunto['descricao']);
    }, $bookCreateCommand->assuntos);

    $book = new Book(
      $bookCreateCommand->titulo,
      $bookCreateCommand->editora,
      $bookCreateCommand->edicao,
      $bookCreateCommand->anoPublicacao,
      $bookCreateCommand->preco,
      $autores,
      $assuntos
    );

    return $this->bookRepository->save($book);
  }
}
