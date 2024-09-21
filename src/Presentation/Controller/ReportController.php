<?php

namespace App\Presentation\Controller;

use App\Application\Service\ReportQueryService;
use App\Infrastructure\Repository\ReportRepository;
use App\Presentation\Enum\HttpCodesEnum;

class ReportController extends BaseController
{
  private ReportQueryService $reportQueryService;

  public function __construct(array $headers, array $body, array $queryParams)
  {
    $reportRepository = new ReportRepository();
    $this->reportQueryService = new ReportQueryService($reportRepository);

    parent::__construct($headers, $body, $queryParams);
  }

  public function generateReport()
  {
    $search = $this->queryParams['search'] ?? '';
    $page = (int) ($this->queryParams['page'] ?? 1);
    $pageSize = (int) ($this->queryParams['pageSize'] ?? 10);
    $offset = ($page - 1) * $pageSize;

    $data = $this->reportQueryService->find($search, $pageSize, $offset);

    if (count($data['books']) == 0) {
      $this->sendErrorResponse(
        ['message' => 'Books not found'], 
        HttpCodesEnum::HTTP_NOT_FOUND
      );
    }
    
    return $this->reportQueryService->generateBookReport($data['books']);
  }
}
