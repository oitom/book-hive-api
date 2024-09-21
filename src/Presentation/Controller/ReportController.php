<?php

namespace App\Presentation\Controller;

use App\Infrastructure\Repository\BookRepository;
use App\Application\Service\BookService;
use App\Application\Service\BookQueyService;
use TCPDF;

class ReportController extends BaseController
{
  private BookService $bookService;
  private BookQueyService $bookQueyService;

  public function __construct(array $headers, array $body, array $queryParams)
  {
    $bookRepository = new BookRepository();
    $this->bookService = new BookService($bookRepository);
    $this->bookQueyService = new BookQueyService($bookRepository);

    parent::__construct($headers, $body, $queryParams);
  }

  public function generateReport()
  {
    $search = $this->queryParams['search'] ?? '';
    $page = (int) ($this->queryParams['page'] ?? 1);
    $pageSize = (int) ($this->queryParams['pageSize'] ?? 10);
    $offset = ($page - 1) * $pageSize;

    $data = $this->bookQueyService->find($search, $pageSize, $offset);

    if (count($data['books']) == 0) {
      $this->sendErrorResponse(['message' => 'Books not found'], 404);
      return;
    }

    return $this->createPDF($data);
  }

  private function createPDF(array $data): void
  {
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->AddPage();
    
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 5, 'Relatório de Livros', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);

    $pdf->Cell(40, 5, 'Título', 1, 0, 'C', 1);
    $pdf->Cell(30, 5, 'Editora', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Edição', 1, 0, 'C', 1);
    $pdf->Cell(40, 5, 'Ano de Publicação', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Preço', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'Autores', 1, 0, 'C', 1);
    $pdf->Cell(70, 5, 'Assuntos', 1, 1, 'C', 1);

    $pdf->SetFillColor(224, 235, 255);
    $pdf->SetTextColor(0);

    $fill = true;
    foreach ($data['books'] as $livro) {
      $fill=!$fill;
      $autores = implode(', ', $livro->autores);
      $assuntos = implode(', ', $livro->assuntos);

      if (strlen($autores) > 40) {
        $autores = substr($autores, 0, 37) . '...';
      }
      if (strlen($assuntos) > 40) {
        $assuntos = substr($assuntos, 0, 37) . '...';
      }
      $pdf->Cell(40, 5, $livro->titulo, 1, 0, 'L', $fill);
      $pdf->Cell(30, 5, $livro->editora, 1, 0, 'L', $fill);
      $pdf->Cell(20, 5, $livro->edicao, 1, 0, 'L', $fill);
      $pdf->Cell(40, 5, $livro->anoPublicacao, 1, 0, 'L', $fill);
      $pdf->Cell(20, 5, $livro->preco, 1, 0, 'L', $fill);
      $pdf->Cell(60, 5, $autores, 1, 0, 'L', $fill);
      $pdf->Cell(70, 5, $assuntos, 1, 1, 'L', $fill); 
    }
    
    $pdf->Output('relatorio_livros.pdf', 'I');
  }
}
