<?php

namespace App\Presentation\Controllers;

use App\Presentation\Enums\HttpCodesEnum;
use App\Presentation\Validators\BookValidator;
use App\Application\Services\BookService;
use App\Application\Services\BookQueyService;
use App\Domain\Commands\BookCommand;
use App\Infrastructure\Repositories\RedisCache;
use App\Infrastructure\Repositories\BookRepository;

class BookController extends BaseController
{
  private BookService $bookService;
  private BookQueyService $bookQueyService;

  public function __construct(array $headers, array $body, array $queryParams)
  {
    $cache = new RedisCache();
    $bookRepository = new BookRepository();
    $this->bookService = new BookService($bookRepository, $cache);
    $this->bookQueyService = new BookQueyService($bookRepository, $cache);

    parent::__construct($headers, $body, $queryParams);
  }

  public function listOneBook(int $id)
  {
    $book = $this->bookQueyService->findOne($id);

    if ($book === null) {
      $this->sendErrorResponse(
        ['message' => 'Book not found'],
        HttpCodesEnum::HTTP_NOT_FOUND
      );
      return;
    }

    $this->sendSuccessResponse(['book' => $book]);
  }

  public function listBooks() : void
  {
    $search = $this->queryParams['search'] ?? '';
    $page = (int) ($this->queryParams['page'] ?? 1);
    $pageSize = (int) ($this->queryParams['pageSize'] ?? 10);
    $offset = ($page - 1) * $pageSize;

    $books = $this->bookQueyService->find($search, $pageSize, $offset);

    if (count($books['books']) == 0) {
      $this->sendErrorResponse(
        ['message' => 'Books not found'],
        HttpCodesEnum::HTTP_NOT_FOUND
      );
      return;
    }

    $this->sendSuccessResponse($books);
  }

  public function createBook() : void
  {
    $validator = new BookValidator();
    $errors = $validator->validate($this->body);

    if (!empty($errors)) {
      $this->sendErrorResponse($errors);
      return;
    }

    $command = new BookCommand($this->body);
    $book = $this->bookService->create($command);

    $this->sendSuccessResponse(
      ['book' => $book],
      'Book created successfully',
      HttpCodesEnum::HTTP_CREATED
    );
  }

  public function updateBook(int $id) : void
  {
    $validator = new BookValidator();
    $errors = $validator->validate($this->body);
    if (!empty($errors)) {
      $this->sendErrorResponse($errors);
      return;
    }

    $updateCommand = new BookCommand($this->body);
    $updatedBook = $this->bookService->update($id, $updateCommand);

    if ($updatedBook === null) {
      $this->sendErrorResponse(
        ['message' => 'Book not found or failed to updated'],
        HttpCodesEnum::HTTP_NOT_FOUND
      );
      return;
    }

    $this->sendSuccessResponse(
      ['book' => $updatedBook],
      'Book updated successfully'
    );
  }

  public function deleteBook(int $id) : void
  {
    $deleted = $this->bookService->delete($id);

    if (! $deleted) {
      $this->sendErrorResponse(
        ['message' => 'Book not found or failed to deleted'],
        HttpCodesEnum::HTTP_NOT_FOUND
      );
      return;
    }

    $this->sendSuccessResponse(
      [],
      'Book deleted successfully'
    );
  }

}
