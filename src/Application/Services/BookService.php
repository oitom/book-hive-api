<?php

namespace App\Application\Services;

use App\Domain\Entities\AuthorEntity;
use App\Domain\Entities\BookEntity;
use App\Domain\Entities\SubjectEntity;
use App\Domain\Repositories\BookRepositoryInterface;
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
      return new AuthorEntity($autor['nome']);
    }, $bookCreateCommand->autores);

    $assuntos = array_map(function($assunto) {
      return new SubjectEntity($assunto['descricao']);
    }, $bookCreateCommand->assuntos);

    $book = new BookEntity(
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
    $existingBook->setUpdatedAt();

    $autores = array_map(function($autor) {
      return new AuthorEntity($autor['nome']);
    }, $bookUpdateCommand->autores);
    $existingBook->setAutores($autores);

    $assuntos = array_map(function($assunto) {
      return new SubjectEntity($assunto['descricao']);
    }, $bookUpdateCommand->assuntos);
    $existingBook->setAssuntos($assuntos);

    return $this->bookRepository->update($id, $existingBook);
  }

  public function delete(int $id): bool
  {
    $book = $this->bookRepository->findOne($id);
    $existingBook = BookMapper::mapOne($book);
    if ($existingBook === null) {
      return false;
    }
    $existingBook = BookMapper::toEntity($existingBook);
    $existingBook->setDeletedAt();
    
    return $this->bookRepository->delete($id, $existingBook);
  }
}
