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

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar mock do PDOConnection e do PDO
        $this->connectionMock = $this->createMock(PDO::class);
        $pdoConnectionMock = $this->createMock(PDOConnection::class);
        
        // Configurar o método getConnection para retornar o mock do PDO
        $pdoConnectionMock->method('getConnection')->willReturn($this->connectionMock);
        
        // Criar instância de ReportRepository
        $this->reportRepository = new ReportRepository();
        $this->reportRepository->setConnection($this->connectionMock); // Define a conexão mock

        // Criar um mock para o PDOStatement
        $this->pdoStatementMock = $this->createMock(PDOStatement::class);
    }

    public function testFindReturnsBooks(): void
    {
        $search = 'Test';
        $pageSize = 10;
        $offset = 0;

        // Configurar o mock para o método prepare do PDO
        $this->connectionMock->method('prepare')
            ->willReturn($this->pdoStatementMock);
        
        // Configurar o método execute do PDOStatement
        $this->pdoStatementMock->method('fetchAll')->willReturn([
            ['titulo' => 'Book 1', 'autores' => 'Author 1', 'assuntos' => 'Subject 1'],
            ['titulo' => 'Book 2', 'autores' => 'Author 2', 'assuntos' => 'Subject 2']
        ]);

        // Configurar o retorno do COUNT
        $countStmtMock = $this->createMock(PDOStatement::class);
        $this->connectionMock->method('prepare')
            ->willReturn($countStmtMock);
        
        $countStmtMock->method('fetchAll')->willReturn([[ 'total' => 2]]);
        
        // Chamar o método
        $result = $this->reportRepository->find($search, $pageSize, $offset);

        // Verificações
        $this->assertCount(2, $result['books']);
        $this->assertEquals('Book 1', $result['books'][0]['titulo']);
        $this->assertEquals('Book 2', $result['books'][1]['titulo']);
    }

    public function testFindWithNoResultsReturnsEmptyArray(): void
    {
        $search = 'Nonexistent';
        $pageSize = 10;
        $offset = 0;

        // Configurar o mock para o método prepare do PDO
        $this->connectionMock->method('prepare')
            ->willReturn($this->pdoStatementMock);
        
        // Configurar o método execute do PDOStatement
        $this->pdoStatementMock->method('fetchAll')->willReturn([]);

        // Configurar o retorno do COUNT
        $countStmtMock = $this->createMock(PDOStatement::class);
        $this->connectionMock->method('prepare')
            ->willReturn($countStmtMock);
        
        $countStmtMock->method('fetchAll')->willReturn([[ 'total' => 0]]);
        
        // Chamar o método
        $result = $this->reportRepository->find($search, $pageSize, $offset);

        // Verificações
        $this->assertCount(0, $result['books']);
    }
}
