<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\CacheInterface;
use Redis as RedisClient;

class RedisCache implements CacheInterface
{
  private RedisClient $redis;

  public function __construct()
  {
    $this->redis = new RedisClient();
    $this->redis->connect($_ENV['CACHE_NAME'], $_ENV['CACHE_PORT']);
  }

  public function set(string $key, mixed $value) : bool
  {
    return $this->redis->set($key, $value, $_ENV['CACHE_TTL']);
  }

  public function get(string $key) : mixed
  {
    return $this->redis->get($key);
  }

  public function delete(string $key) : bool
  {
    return $this->redis->del($key) > 0;
  }

  public function clear(string $pattern = null) : bool
  {
    if ($pattern) {
      $keys = $this->redis->keys($pattern);
      if (! empty($keys)) {
        return $this->redis->del($keys) > 0;
      }
    } else {
      return $this->redis->flushAll();
    }
    return false;
  }
}
