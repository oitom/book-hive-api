<?php

namespace App\Application\Services;

use App\Domain\Repositories\ReportRepositoryInterface;
use App\Application\Mappers\BookMapper;
use TCPDF;

class ReportQueryService
{
  private TCPDF $pdf;
  private ReportRepositoryInterface $reportRepository;

  public function __construct(ReportRepositoryInterface $reportRepositoryInterface)
  {
    $this->reportRepository = $reportRepositoryInterface;
    $this->pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  }

  public function setPdf(TCPDF $pdf): void
  {
    $this->pdf = $pdf;
  }

  public function find(string $search, int $pageSize, int $offset): array|null
  {
    $books = $this->reportRepository->find($search, $pageSize, $offset);

    return BookMapper::mapList($books);
  }

  public function generateBookReport($books): bool
  {
    $this->pdf->AddPage();
    $this->pdf->SetFont('helvetica', 'B', 14);
    $this->pdf->Cell(0, 15, 'Relatório de Livros', 0, 1, 'C');
    $this->pdf->SetFont('helvetica', '', 10);
    $this->pdf->SetFillColor(100, 100, 100);
    $this->pdf->SetTextColor(255, 255, 255);

    $this->pdf->Cell(40, 5, 'Título', 1, 0, 'C', 1);
    $this->pdf->Cell(30, 5, 'Editora', 1, 0, 'C', 1);
    $this->pdf->Cell(20, 5, 'Edição', 1, 0, 'C', 1);
    $this->pdf->Cell(20, 5, 'Ano Pub.', 1, 0, 'C', 1);
    $this->pdf->Cell(25, 5, 'Preço', 1, 0, 'C', 1);
    $this->pdf->Cell(60, 5, 'Autores', 1, 0, 'C', 1);
    $this->pdf->Cell(80, 5, 'Assuntos', 1, 1, 'C', 1);

    $this->pdf->SetFillColor(224, 235, 255);
    $this->pdf->SetTextColor(0);

    $fill = true;
    foreach ($books as $livro) {
      $fill=!$fill;
      $autores = implode(', ', $livro->autores);
      $assuntos = implode(', ', $livro->assuntos);

      if (strlen($autores) > 40) {
        $autores = substr($autores, 0, 40) . '...';
      }
      if (strlen($assuntos) > 45) {
        $assuntos = substr($assuntos, 0, 45) . '...';
      }
      $this->pdf->Cell(40, 5, $livro->titulo, 1, 0, 'L', $fill);
      $this->pdf->Cell(30, 5, $livro->editora, 1, 0, 'C', $fill);
      $this->pdf->Cell(20, 5, $livro->edicao, 1, 0, 'C', $fill);
      $this->pdf->Cell(20, 5, $livro->anoPublicacao, 1, 0, 'C', $fill);

      $preco = "R$ " . number_format($livro->preco, 2, ',', '.');
      $this->pdf->Cell(25, 5, $preco, 1, 0, 'C', $fill);
      
      $this->pdf->Cell(60, 5, $autores, 1, 0, 'L', $fill);
      $this->pdf->Cell(80, 5, $assuntos, 1, 1, 'L', $fill);
    }
    $this->pdf->Output('relatorio_livros.pdf', 'D');
    
    return true;
  }
}
