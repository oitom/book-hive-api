<?php

namespace Tests\App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\RedisCache;
use PHPUnit\Framework\TestCase;
use Redis as RedisClient;

class RedisCacheTest extends TestCase
{
  private $redisMock;
  private $redisCache;

  protected function setUp() : void
  {
    parent::setUp();

    $this->redisMock = $this->createMock(RedisClient::class);
    $this->redisCache = new RedisCache();
    $this->redisCacheReflection = new \ReflectionClass($this->redisCache);
    $this->redisProperty = $this->redisCacheReflection->getProperty('redis');
    $this->redisProperty->setAccessible(true);
    $this->redisProperty->setValue($this->redisCache, $this->redisMock);
  }

  public function testSet() : void
  {
    $key = 'test_key';
    $value = 'test_value';

    $this->redisMock->expects($this->once())
      ->method('set')
      ->with($key, $value, $_ENV['CACHE_TTL'])
      ->willReturn(true);

    $result = $this->redisCache->set($key, $value);
    $this->assertTrue($result);
  }

  public function testGet() : void
  {
    $key = 'test_key';
    $value = 'test_value';

    $this->redisMock->expects($this->once())
      ->method('get')
      ->with($key)
      ->willReturn($value);

    $result = $this->redisCache->get($key);
    $this->assertEquals($value, $result);
  }

  public function testDelete() : void
  {
    $key = 'test_key';

    $this->redisMock->expects($this->once())
      ->method('del')
      ->with($key)
      ->willReturn(1);

    $result = $this->redisCache->delete($key);
    $this->assertTrue($result);
  }

  public function testClearWithPattern() : void
  {
    $pattern = 'test:*';
    $keys = ['test:key1', 'test:key2'];

    $this->redisMock->expects($this->once())
      ->method('keys')
      ->with($pattern)
      ->willReturn($keys);

    $this->redisMock->expects($this->once())
      ->method('del')
      ->with($keys)
      ->willReturn(2);

    $result = $this->redisCache->clear($pattern);
    $this->assertTrue($result);
  }

  public function testClearWithoutPattern() : void
  {
    $this->redisMock->expects($this->once())
      ->method('flushAll')
      ->willReturn(true);

    $result = $this->redisCache->clear();
    $this->assertTrue($result);
  }
}
