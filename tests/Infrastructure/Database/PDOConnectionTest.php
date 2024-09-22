<?php

namespace Tests\App\Infrastructure\Database;

use App\Infrastructure\Database\PDOConnection;
use PHPUnit\Framework\TestCase;

class PDOConnectionTest extends TestCase
{
  private string $originalDbHost;
  private string $originalDbName;
  private int $originalDbPort;
  private string $originalDbUser;
  private string $originalDbPassword;

  protected function setUp(): void
  {
    $this->originalDbHost = $_ENV['DB_HOST'];
    $this->originalDbName = $_ENV['DB_DATABASE'];
    $this->originalDbPort = $_ENV['DB_PORT'];
    $this->originalDbUser = $_ENV['DB_USERNAME'];
    $this->originalDbPassword = $_ENV['DB_PASSWORD'];

    $_ENV['DB_HOST'] = 'localhost';
    $_ENV['DB_DATABASE'] = 'test_db';
    $_ENV['DB_PORT'] = 3306;
    $_ENV['DB_USERNAME'] = 'root';
    $_ENV['DB_PASSWORD'] = 'password';
  }

  protected function tearDown(): void
  {
    $_ENV['DB_HOST'] = $this->originalDbHost;
    $_ENV['DB_DATABASE'] = $this->originalDbName;
    $_ENV['DB_PORT'] = $this->originalDbPort;
    $_ENV['DB_USERNAME'] = $this->originalDbUser;
    $_ENV['DB_PASSWORD'] = $this->originalDbPassword;
  }

  public function testConnectionThrowsExceptionOnError(): void
  {
    $_ENV['DB_HOST'] = 'invalid_host';
    $_ENV['DB_DATABASE'] = 'invalid_db';

    http_response_code(200);
    ob_start();

    new PDOConnection();
    $output = ob_get_clean();
    $this->assertEquals(500, http_response_code());

    $expectedOutput = json_encode(['message' => 'Não foi possível estabelecer uma conexão']);
    $this->assertEquals($expectedOutput, $output);
  }
}
