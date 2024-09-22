<?php

namespace App\Application\Services;

use App\Application\Dtos\BookDto;
use App\Application\Mappers\BookMapper;
use App\Domain\Repositories\CacheInterface;
use App\Domain\Repositories\BookRepositoryInterface;

class BookQueyService
{
  private BookRepositoryInterface $bookRepository;
  private CacheInterface $cache;

  public function __construct(BookRepositoryInterface $bookRepository, CacheInterface $cache)
  {
    $this->bookRepository = $bookRepository;
    $this->cache = $cache;
  }

  public function findOne(int $id): BookDto|null
  {
    $cacheKey = "book_$id";
    $cachedBook = $this->cache->get($cacheKey);

    if ($cachedBook) {
      $book = json_decode($cachedBook, TRUE);
    } else {
      $book = $this->bookRepository->findOne($id);
      if ($book) {
        $this->cache->set($cacheKey, json_encode($book));
      }
    }

    return BookMapper::mapOne($book);
  }

  public function find(string $search, int $pageSize, int $offset): array|null
  {
    $cacheKey = "books_search_{$search}_pageSize_{$pageSize}_offset_{$offset}";
    $cachedBooks = $this->cache->get($cacheKey);
    
    if ($cachedBooks) {
      $books = json_decode($cachedBooks, TRUE);
    } else { 
      $books = $this->bookRepository->find($search, $pageSize, $offset);

      if (count($books['books']) > 0) {
        $this->cache->set($cacheKey, json_encode($books));
      }
    }

    return BookMapper::mapList($books);
  }
}
