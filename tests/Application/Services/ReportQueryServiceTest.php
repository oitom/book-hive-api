<?php

namespace Tests\App\Application\Services;

use App\Application\Services\ReportQueryService;
use App\Domain\Repositories\ReportRepositoryInterface;
use PHPUnit\Framework\TestCase;
use TCPDF;

class ReportQueryServiceTest extends TestCase
{
    private ReportQueryService $reportQueryService;
    private $reportRepositoryMock;
    private $tcpdfMock;

    protected function setUp(): void
    {
        $this->reportRepositoryMock = $this->createMock(ReportRepositoryInterface::class);
        $this->tcpdfMock = $this->createMock(TCPDF::class);
        
        // Cria a instância do serviço
        $this->reportQueryService = new ReportQueryService($this->reportRepositoryMock);
        $this->reportQueryService->setPdf($this->tcpdfMock);
    }

    public function testFindReturnsMappedBooks()
    {
        $search = 'some search';
        $pageSize = 10;
        $offset = 0;
        $expectedBooks = [
            (object) ['titulo' => 'Livro 1', 'editora' => 'Editora 1', 'edicao' => '1st', 'anoPublicacao' => 2021, 'preco' => 39.90, 'autores' => ['Autor 1'], 'assuntos' => ['Assunto 1']],
        ];

        $this->reportRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($search, $pageSize, $offset)
            ->willReturn($expectedBooks);

        $result = $this->reportQueryService->find($search, $pageSize, $offset);

        $this->assertNotNull($result);
    }

    public function testGenerateBookReportReturnsTrue()
    {
        $books = [
            (object) ['titulo' => 'Livro 1', 'editora' => 'Editora 1', 'edicao' => 1, 'anoPublicacao' => 2021, 'preco' => 39.90, 'autores' => ['Autor 1111111111111111111111111111111111111111111111111111111111'], 'assuntos' => ['Assunto 1111111111111111111111111111111111111111111111111']],
            (object) ['titulo' => 'Livro 2', 'editora' => 'Editora 2', 'edicao' => 2, 'anoPublicacao' => 2022, 'preco' => 49.90, 'autores' => ['Autor 2'], 'assuntos' => ['Assunto 2']],
        ];
        // Chama o método que gera o relatório
        $result = $this->reportQueryService->generateBookReport($books);
        $this->assertTrue($result);
    }
}
