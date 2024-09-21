<?php

namespace App\Application\Service;

use App\Domain\Repositories\BookRepositoryInterface;
use App\Domain\Entity\Book;
use App\Domain\Entity\Author;
use App\Domain\Entity\Subject;
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
      return new Author($autor['nome']);
    }, $bookCreateCommand->autores);

    $assuntos = array_map(function($assunto) {
      return new Subject($assunto['descricao']);
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
