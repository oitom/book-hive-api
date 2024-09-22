<?php

namespace App\Domain\Repositories;

interface CacheInterface
{
  public function set(string $key, mixed $value): bool;

  public function get(string $key): mixed;

  public function delete(string $key): bool;
  
  public function clear(string $pattern = null): bool;
}
