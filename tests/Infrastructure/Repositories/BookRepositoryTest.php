<?php

namespace Tests\App\Infrastructure\Repositories;

use App\Domain\Entities\BookEntity;
use App\Infrastructure\Repositories\BookRepository;
use App\Infrastructure\Repositories\AuthorRepository;
use App\Infrastructure\Repositories\SubjectRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class BookRepositoryTest extends TestCase
{
  private $connectionMock;
  private $bookRepository;
  private $authorRepositoryMock;
  private $subjectRepositoryMock;
  private $stmtMock;

  protected function setUp() : void
  {
    parent::setUp();

    $this->connectionMock = $this->createMock(PDO::class);
    $this->stmtMock = $this->createMock(PDOStatement::class);
    $this->connectionMock->method('prepare')->willReturn($this->stmtMock);
    $this->bookRepository = new BookRepository();
    $this->bookRepository->setConnection($this->connectionMock);
    $this->authorRepositoryMock = $this->createMock(AuthorRepository::class);
    $this->subjectRepositoryMock = $this->createMock(SubjectRepository::class);
    $this->bookRepository->setAuthorRepository($this->authorRepositoryMock);
    $this->bookRepository->setSubjectRepository($this->subjectRepositoryMock);
  }

  public function testSaveBook() : void
  {
    $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);

    $this->stmtMock->expects($this->any())
      ->method('execute')
      ->willReturn(true);

    $this->authorRepositoryMock
      ->expects($this->once())
      ->method('saveAll')
      ->with($this->anything(), $this->anything());

    $this->subjectRepositoryMock
      ->expects($this->once())
      ->method('saveAll')
      ->with($this->anything(), $this->anything());

    $result = $this->bookRepository->save($book);
    $this->assertTrue($result);
  }

  public function testSaveBookThrowsException() : void
  {
    $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);

    $this->stmtMock->expects($this->once())
      ->method('execute')
      ->will($this->throwException(new \Exception('Database error')));

    $result = $this->bookRepository->save($book);
    $this->assertFalse($result);
  }

  public function testFindOneReturnsBook() : void
  {
    $id = 1;
    $this->stmtMock->method('fetch')->willReturn([
      'id'            => $id,
      'titulo'        => 'Test Book',
      'editora'       => 'Test Publisher',
      'edicao'        => 1,
      'anoPublicacao' => 2023,
      'preco'         => 29.99,
    ]);

    $result = $this->bookRepository->findOne($id);
    $this->assertIsArray($result);
    $this->assertEquals($id, $result['id']);
  }

  public function testFindReturnsBooks() : void
  {
    $this->stmtMock->method('fetchAll')->willReturn([[
      'id'            => 1,
      'titulo'        => 'Test Book',
      'editora'       => 'Test Publisher',
      'edicao'        => 1,
      'anoPublicacao' => 2023,
      'preco'         => 29.99,
    ]]);

    $result = $this->bookRepository->find('', 10, 0);
    $this->assertArrayHasKey('books', $result);
    $this->assertNotEmpty($result['books']);
  }

  public function testUpdateBook() : void
  {
    $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);
    $id = 1;

    $this->stmtMock->expects($this->any())
      ->method('execute')
      ->willReturn(true);

    $this->authorRepositoryMock
      ->expects($this->once())
      ->method('deleteAllByBookId')
      ->with($id);

    $this->authorRepositoryMock
      ->expects($this->once())
      ->method('saveAll')
      ->with($this->anything(), $id);

    $this->subjectRepositoryMock
      ->expects($this->once())
      ->method('deleteAllByBookId')
      ->with($id);

    $this->subjectRepositoryMock
      ->expects($this->once())
      ->method('saveAll')
      ->with($this->anything(), $id);

    $result = $this->bookRepository->update($id, $book);
    $this->assertTrue($result);
  }

  public function testDeleteBook() : void
  {
    $id = 1;
    $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);

    $this->stmtMock->expects($this->any())
      ->method('execute')
      ->willReturn(true);

    $result = $this->bookRepository->delete($id, $book);
    $this->assertTrue($result);
  }

  public function testFindWithSearch() : void
  {
    $search = 'Test';
    $pageSize = 10;
    $offset = 0;

    $this->stmtMock->method('fetchColumn')->willReturn(1);

    $this->stmtMock->method('fetchAll')->willReturn([[
      'id'            => 1,
      'titulo'        => 'Test Book',
      'editora'       => 'Test Publisher',
      'edicao'        => 1,
      'anoPublicacao' => 2023,
      'preco'         => 29.99,
    ]]);

    $result = $this->bookRepository->find($search, $pageSize, $offset);

    $this->assertArrayHasKey('books', $result);
    $this->assertNotEmpty($result['books']);
    $this->assertEquals(1, $result['pagination']['count']);
    $this->assertEquals(1, $result['pagination']['countPages']);
    $this->assertEquals(1, $result['pagination']['currentPage']);
  }

  public function testFindWithoutSearch() : void
  {
    $search = '';
    $pageSize = 10;
    $offset = 0;

    $this->stmtMock->method('fetchColumn')->willReturn(0);
    $this->stmtMock->method('fetchAll')->willReturn([]);

    $result = $this->bookRepository->find($search, $pageSize, $offset);

    $this->assertArrayHasKey('books', $result);
    $this->assertEmpty($result['books']);
    $this->assertEquals(0, $result['pagination']['count']);
    $this->assertEquals(0, $result['pagination']['countPages']);
    $this->assertEquals(1, $result['pagination']['currentPage']);
  }
}
