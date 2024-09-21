<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\BookEntity;

interface BookRepositoryInterface
{
  public function save(BookEntity $book): bool;
  public function findOne(int $id): array | null;
  public function find(string $search, int $pageSize, int $offset): array | null;
  public function update(int $bookId, BookEntity $book): bool;
  public function delete(int $id,  BookEntity $book): bool;
}
