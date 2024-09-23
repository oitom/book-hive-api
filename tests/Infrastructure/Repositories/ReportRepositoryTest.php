<?php

namespace Tests\App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\ReportRepository;
use App\Infrastructure\Database\PDOConnection;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ReportRepositoryTest extends TestCase
{
  private $connectionMock;
  private $pdoStatementMock;
  private ReportRepository $reportRepository;

  protected function setUp() : void
  {
    parent::setUp();

    $this->connectionMock = $this->createMock(PDO::class);
    $pdoConnectionMock = $this->createMock(PDOConnection::class);

    $pdoConnectionMock->method('getConnection')->willReturn($this->connectionMock);

    $this->reportRepository = new ReportRepository();
    $this->reportRepository->setConnection($this->connectionMock);

    $this->pdoStatementMock = $this->createMock(PDOStatement::class);
  }

  public function testFindReturnsBooks() : void
  {
    $search = 'Test';
    $pageSize = 10;
    $offset = 0;

    $this->connectionMock->method('prepare')
      ->willReturn($this->pdoStatementMock);

    $this->pdoStatementMock->method('fetchAll')->willReturn([
      ['titulo' => 'Book 1', 'autores' => 'Author 1', 'assuntos' => 'Subject 1'],
      ['titulo' => 'Book 2', 'autores' => 'Author 2', 'assuntos' => 'Subject 2'],
    ]);

    $countStmtMock = $this->createMock(PDOStatement::class);
    $this->connectionMock->method('prepare')
      ->willReturn($countStmtMock);

    $countStmtMock->method('fetchAll')->willReturn([['total' => 2]]);

    $result = $this->reportRepository->find($search, $pageSize, $offset);

    $this->assertCount(2, $result['books']);
    $this->assertEquals('Book 1', $result['books'][0]['titulo']);
    $this->assertEquals('Book 2', $result['books'][1]['titulo']);
  }

  public function testFindWithNoResultsReturnsEmptyArray() : void
  {
    $search = 'Nonexistent';
    $pageSize = 10;
    $offset = 0;

    $this->connectionMock->method('prepare')
      ->willReturn($this->pdoStatementMock);

    $this->pdoStatementMock->method('fetchAll')->willReturn([]);

    $countStmtMock = $this->createMock(PDOStatement::class);
    $this->connectionMock->method('prepare')
      ->willReturn($countStmtMock);

    $countStmtMock->method('fetchAll')->willReturn([['total' => 0]]);
    $result = $this->reportRepository->find($search, $pageSize, $offset);

    $this->assertCount(0, $result['books']);
  }
}
