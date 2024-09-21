<?php

namespace App\Domain\Repositories;

use App\Domain\Entity\Book;

interface BookRepositoryInterface
{
  public function save(Book $book): bool;
  public function findOne(int $id): array | null;
  public function find(string $search, int $pageSize, int $offset): array | null;
  public function update(int $bookId, Book $book): bool;
  public function delete(int $id,  Book $book): bool;
}
