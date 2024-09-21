<?php

namespace App\Application\Service;

use App\Domain\Repositories\BookRepositoryInterface;
use App\Domain\Entity\Book;
use App\Domain\Entity\Author;
use App\Domain\Entity\Subject;
use App\Domain\Commands\BookCommand;
use App\Application\Mappers\BookMapper;

class BookService
{
  private BookRepositoryInterface $bookRepository;

  public function __construct(BookRepositoryInterface $bookRepository)
  {
    $this->bookRepository = $bookRepository;
  }

  public function create(BookCommand $bookCreateCommand): bool
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

  public function update(int $id, BookCommand $bookUpdateCommand): bool | null
  {
    $book = $this->bookRepository->findOne($id);
    $existingBook = BookMapper::mapOne($book);

    if ($existingBook === null) {
      return null;
    }
    $existingBook = BookMapper::toEntity($existingBook);
    $existingBook->setTitulo($bookUpdateCommand->titulo);
    $existingBook->setEditora($bookUpdateCommand->editora);
    $existingBook->setEdicao($bookUpdateCommand->edicao);
    $existingBook->setAnoPublicacao($bookUpdateCommand->anoPublicacao);
    $existingBook->setPreco($bookUpdateCommand->preco);

    $autores = array_map(function($autor) {
        return new Author($autor['nome']);
    }, $bookUpdateCommand->autores);
    $existingBook->setAutores($autores);

    $assuntos = array_map(function($assunto) {
        return new Subject($assunto['descricao']);
    }, $bookUpdateCommand->assuntos);
    $existingBook->setAssuntos($assuntos);

    return $this->bookRepository->update($id, $existingBook);
  }
}
