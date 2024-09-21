<?php

namespace App\Domain\Repositories;

interface ReportRepositoryInterface
{
  public function find(string $search, int $pageSize, int $offset): array | null;
}
