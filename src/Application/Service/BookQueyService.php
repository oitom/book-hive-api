<?php

namespace App\Application\Service;

use App\Application\Dtos\BookDto;
use App\Domain\Repositories\BookRepositoryInterface;
use App\Application\Mappers\BookMapper;

class BookQueyService
{
  private BookRepositoryInterface $bookRepository;

  public function __construct(BookRepositoryInterface $bookRepository)
  {
    $this->bookRepository = $bookRepository;
  }

  public function findOne(int $id): BookDto|null
  {
    $book = $this->bookRepository->findOne($id);
    return BookMapper::mapOne($book);
  }
  public function find(): array|null
  {
    $books = $this->bookRepository->find();
    return BookMapper::mapList($books);
  }
}
