<?php

namespace Tests\Presentation\Routes;

use PHPUnit\Framework\TestCase;

class ApiRoutesTest extends TestCase
{
  private array $routes;

  protected function setUp(): void
  {
    $this->routes = include __DIR__ . '/../../../src/Presentation/Routers/routes.php';
  }

  public function testPostBooksRoute(): void
  {
    $this->assertArrayHasKey('POST', $this->routes);
    $this->assertArrayHasKey('/books', $this->routes['POST']);
    $this->assertEquals('App\Presentation\Controllers\BookController@createBook', $this->routes['POST']['/books']);
  }

  public function testGetBooksRoute(): void
  {
    $this->assertArrayHasKey('GET', $this->routes);
    $this->assertArrayHasKey('/books', $this->routes['GET']);
    $this->assertEquals('App\Presentation\Controllers\BookController@listBooks', $this->routes['GET']['/books']);
  }

  public function testGetBooksByIdRoute(): void
  {
    $this->assertArrayHasKey('GET', $this->routes);
    $this->assertArrayHasKey('/books/:id', $this->routes['GET']);
    $this->assertEquals('App\Presentation\Controllers\BookController@listOneBook', $this->routes['GET']['/books/:id']);
  }

  public function testGetReportRoute(): void
  {
    $this->assertArrayHasKey('GET', $this->routes);
    $this->assertArrayHasKey('/report', $this->routes['GET']);
    $this->assertEquals('App\Presentation\Controllers\ReportController@generateReport', $this->routes['GET']['/report']);
  }

  public function testPutBooksByIdRoute(): void
  {
    $this->assertArrayHasKey('PUT', $this->routes);
    $this->assertArrayHasKey('/books/:id', $this->routes['PUT']);
    $this->assertEquals('App\Presentation\Controllers\BookController@updateBook', $this->routes['PUT']['/books/:id']);
  }

  public function testDeleteBooksByIdRoute(): void
  {
    $this->assertArrayHasKey('DELETE', $this->routes);
    $this->assertArrayHasKey('/books/:id', $this->routes['DELETE']);
    $this->assertEquals('App\Presentation\Controllers\BookController@deleteBook', $this->routes['DELETE']['/books/:id']);
  }
}
