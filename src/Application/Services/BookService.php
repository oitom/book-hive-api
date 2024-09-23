<?php

namespace App\Application\Services;

use App\Application\Mappers\BookMapper;
use App\Domain\Entities\AuthorEntity;
use App\Domain\Entities\BookEntity;
use App\Domain\Entities\SubjectEntity;
use App\Domain\Commands\BookCommand;
use App\Domain\Repositories\CacheInterface;
use App\Domain\Repositories\BookRepositoryInterface;

class BookService
{
  private BookRepositoryInterface $bookRepository;
  private CacheInterface $cache;

  public function __construct(BookRepositoryInterface $bookRepository, CacheInterface $cache)
  {
    $this->bookRepository = $bookRepository;
    $this->cache = $cache;
  }

  public function create(BookCommand $bookCreateCommand) : bool
  {
    $autores = array_map(function ($autor) {
      return new AuthorEntity($autor['nome']);
    }, $bookCreateCommand->autores);

    $assuntos = array_map(function ($assunto) {
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
    $result = $this->bookRepository->save($book);

    if ($result) {
      $this->cache->clear('books_search_*');
    }

    return $result;
  }

  public function update(int $id, BookCommand $bookUpdateCommand) : bool|null
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

    $autores = array_map(function ($autor) {
      return new AuthorEntity($autor['nome']);
    }, $bookUpdateCommand->autores);

    $existingBook->setAutores($autores);

    $assuntos = array_map(function ($assunto) {
      return new SubjectEntity($assunto['descricao']);
    }, $bookUpdateCommand->assuntos);

    $existingBook->setAssuntos($assuntos);

    $result = $this->bookRepository->update($id, $existingBook);

    if ($result) {
      $this->cache->delete("book_{$id}");
      $this->cache->clear('books_search_*');
    }

    return $result;
  }

  public function delete(int $id) : bool
  {
    $book = $this->bookRepository->findOne($id);
    $existingBook = BookMapper::mapOne($book);

    if ($existingBook === null) {
      return false;
    }

    $existingBook = BookMapper::toEntity($existingBook);
    $existingBook->setDeletedAt();

    $result = $this->bookRepository->delete($id, $existingBook);

    if ($result) {
      $this->cache->delete("book_{$id}");
      $this->cache->clear('books_search_*');
    }

    return $result;
  }
}
