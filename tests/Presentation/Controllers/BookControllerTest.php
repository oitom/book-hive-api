<?php

use PHPUnit\Framework\TestCase;
use App\Presentation\Controllers\BookController;
use App\Application\Services\BookService;
use App\Application\Services\BookQueyService;
use App\Application\Dtos\BookDto;
use App\Presentation\Enums\HttpCodesEnum;

class BookControllerTest extends TestCase
{
  private BookController $bookController;
  private $bookServiceMock;
  private $bookQueyServiceMock;

  protected function setUp() : void
  {
    $headers = [];
    $body = [];
    $queryParams = [];

    $this->bookServiceMock = $this->createMock(BookService::class);
    $this->bookQueyServiceMock = $this->createMock(BookQueyService::class);

    $this->bookController = $this->getMockBuilder(BookController::class)
      ->setConstructorArgs([$headers, $body, $queryParams])
      ->onlyMethods(['sendErrorResponse', 'sendSuccessResponse'])
      ->getMock();

    $reflectionBookService = new \ReflectionProperty(BookController::class, 'bookService');
    $reflectionBookService->setAccessible(true);
    $reflectionBookService->setValue($this->bookController, $this->bookServiceMock);

    $reflectionBookQueyService = new \ReflectionProperty(BookController::class, 'bookQueyService');
    $reflectionBookQueyService->setAccessible(true);
    $reflectionBookQueyService->setValue($this->bookController, $this->bookQueyServiceMock);
  }

  public function testListOneBookSuccess() : void
  {
    $bookId = 1;
    $bookDto = new BookDto($bookId, 'Book Title', 'Publisher', 1, 2023, 10.99, [], []);

    $this->bookQueyServiceMock->expects($this->once())
      ->method('findOne')
      ->with($bookId)
      ->willReturn($bookDto);

    $this->bookController->expects($this->once())
      ->method('sendSuccessResponse')
      ->with(['book' => $bookDto]);

    $this->bookController->listOneBook($bookId);
  }

  public function testListOneBookNotFound() : void
  {
    $bookId = 1;

    $this->bookQueyServiceMock->expects($this->once())
      ->method('findOne')
      ->with($bookId)
      ->willReturn(null);

    $this->bookController->expects($this->once())
      ->method('sendErrorResponse')
      ->with(['message' => 'Book not found'], HttpCodesEnum::HTTP_NOT_FOUND);

    $this->bookController->listOneBook($bookId);
  }

  public function testListBooksSuccess() : void
  {
    $search = '';
    $pageSize = 10;
    $offset = 0;
    $books = ['books' => [['id' => 1, 'title' => 'Book Title']]];

    $this->bookQueyServiceMock->expects($this->once())
      ->method('find')
      ->with($search, $pageSize, $offset)
      ->willReturn($books);

    $this->bookController->expects($this->once())
      ->method('sendSuccessResponse')
      ->with($books);

    $this->bookController->listBooks();
  }

  public function testListBooksNotFound() : void
  {
    $search = '';
    $pageSize = 10;
    $offset = 0;
    $books = ['books' => []];

    $this->bookQueyServiceMock->expects($this->once())
      ->method('find')
      ->with($search, $pageSize, $offset)
      ->willReturn($books);

    $this->bookController->expects($this->once())
      ->method('sendErrorResponse')
      ->with(['message' => 'Books not found'], HttpCodesEnum::HTTP_NOT_FOUND);

    $this->bookController->listBooks();
  }

  public function testCreateBookValidationError() : void
  {
    $bookData = ['titulo' => ''];

    $reflectionBody = new \ReflectionProperty(BookController::class, 'body');
    $reflectionBody->setAccessible(true);
    $reflectionBody->setValue($this->bookController, $bookData);

    $validationErrors = [
      'titulo'        => 'titulo é obrigatório.',
      'editora'       => 'editora é obrigatório.',
      'edicao'        => 'edicao é obrigatório.',
      'anoPublicacao' => 'anoPublicacao é obrigatório.',
      'preco'         => 'preco é obrigatório.',
      'autores'       => 'autores é obrigatório.',
      'assuntos'      => 'assuntos é obrigatório.',
    ];

    $this->bookServiceMock->expects($this->never())
      ->method('create');

    $this->bookController->expects($this->once())
      ->method('sendErrorResponse')
      ->with($validationErrors, HttpCodesEnum::HTTP_BAD_REQUEST);

    $this->bookController->createBook();
  }

  public function testDeleteBookSuccess() : void
  {
    $bookId = 1;

    $this->bookServiceMock->expects($this->once())
      ->method('delete')
      ->with($bookId)
      ->willReturn(true);

    $this->bookController->expects($this->once())
      ->method('sendSuccessResponse')
      ->with([], 'Book deleted successfully');

    $this->bookController->deleteBook($bookId);
  }

  public function testDeleteBookNotFound() : void
  {
    $bookId = 1;

    $this->bookServiceMock->expects($this->once())
      ->method('delete')
      ->with($bookId)
      ->willReturn(false);

    $this->bookController->expects($this->once())
      ->method('sendErrorResponse')
      ->with(['message' => 'Book not found or failed to deleted'], HttpCodesEnum::HTTP_NOT_FOUND);

    $this->bookController->deleteBook($bookId);
  }
}
