<?php

namespace Tests\App\Application\Services;

use App\Application\Services\BookQueyService;
use App\Application\Mappers\BookMapper;
use App\Application\Dtos\BookDto;
use PHPUnit\Framework\TestCase;
use App\Domain\Repositories\CacheInterface;
use App\Domain\Repositories\BookRepositoryInterface;

class BookQueryServiceTest extends TestCase
{
  private BookRepositoryInterface $bookRepository;
  private CacheInterface $cache;
  private BookQueyService $bookQueryService;

  protected function setUp(): void
  {
    $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
    $this->cache = $this->createMock(CacheInterface::class);
    $this->bookQueryService = new BookQueyService($this->bookRepository, $this->cache);
  }

  public function testFindOneFromCache(): void
  {
    $bookId = 1;
    $cachedBook = [
      'id' => 1,
      'titulo' => 'Cached Book',
      'editora' => 'Cached Publisher',
      'edicao' => 1,
      'anoPublicacao' => 2022,
      'preco' => 19.99,
      'autores' => 'Author Cached',
      'assuntos' => 'Subject Cached'
    ];

    $this->cache->method('get')->willReturn(json_encode($cachedBook));
    $result = $this->bookQueryService->findOne($bookId);

    $this->assertInstanceOf(BookDto::class, $result);
    $this->assertEquals('Cached Book', $result->titulo);
  }

  public function testFindOneFromRepository(): void
  {
    $bookId = 2;
    $bookData = [
      'id' => 2,
      'titulo' => 'Repository Book',
      'editora' => 'Repository Publisher',
      'edicao' => 2,
      'anoPublicacao' => 2021,
      'preco' => 29.99,
      'autores' => 'Author Repo',
      'assuntos' => 'Subject Repo'
    ];

    $this->cache->method('get')->willReturn(null);
    $this->bookRepository->method('findOne')->willReturn($bookData);

    $this->cache->expects($this->once())
      ->method('set')
      ->with("book_$bookId", json_encode($bookData));

    $result = $this->bookQueryService->findOne($bookId);

    $this->assertInstanceOf(BookDto::class, $result);
    $this->assertEquals('Repository Book', $result->titulo);
  }

  public function testFindReturnsCachedData(): void
  {
    $searchTerm = 'Test';
    $pageSize = 10;
    $offset = 0;
    $cachedBooks = [
      'books' => [],
      'pagination' => ['total' => 0, 'currentPage' => 1, 'totalPages' => 0]
    ];

    $this->cache->method('get')->willReturn(json_encode($cachedBooks));

    $result = $this->bookQueryService->find($searchTerm, $pageSize, $offset);

    $this->assertIsArray($result);
    $this->assertEquals($cachedBooks['books'], $result['books']);
  }

  public function testFindReturnsFromRepository(): void
  {
    $searchTerm = 'Test';
    $pageSize = 10;
    $offset = 0;
    $bookData = [
      'books' => [
        [
          'id' => 3,
          'titulo' => 'Found Book',
          'editora' => 'Found Publisher',
          'edicao' => 3,
          'anoPublicacao' => 2020,
          'preco' => 15.99,
          'autores' => 'Author Found',
          'assuntos' => 'Subject Found'
        ]
      ],
      'pagination' => ['total' => 1, 'currentPage' => 1, 'totalPages' => 1]
    ];

    $this->cache->method('get')->willReturn(null);
    $this->bookRepository->method('find')->willReturn($bookData);

    $this->cache->expects($this->once())
      ->method('set')
      ->with("books_search_{$searchTerm}_pageSize_{$pageSize}_offset_{$offset}", json_encode($bookData));

    $result = $this->bookQueryService->find($searchTerm, $pageSize, $offset);

    $this->assertIsArray($result);
    $this->assertCount(1, $result['books']);
    $this->assertEquals('Found Book', $result['books'][0]->titulo);
  }
}
