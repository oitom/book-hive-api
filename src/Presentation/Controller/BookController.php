<?php

namespace App\Presentation\Controller;

use App\Presentation\Validator\BookValidator;
use App\Infrastructure\Repository\BookRepository;
use App\Application\Service\BookService;
use App\Application\Service\BookQueyService;
use App\Domain\Commands\BookCommand;

class BookController extends BaseController
{
  private BookService $bookService;
  private BookQueyService $bookQueyService;

  public function __construct(array $headers, array $body, array $queryParams)
  {
      $bookRepository = new BookRepository();
      $this->bookService = new BookService($bookRepository);
      $this->bookQueyService = new BookQueyService($bookRepository);

      parent::__construct($headers, $body, $queryParams);
  }

  public function listOneBook(int $id)
  {
    $book = $this->bookQueyService->findOne($id);

    if ($book === null) {
      $this->sendErrorResponse(['message' => 'Book not found'], 404);
    }

    return json_encode(['book' => $book]);
  }

  public function listBooks()
  {
    $books = $this->bookQueyService->find();

    if ($books === null) {
      $this->sendErrorResponse(['message' => 'Books not found'], 404);
    }

    return json_encode(['books' => $books]);
  }

  public function createBook()
  {
    $validator = new BookValidator();
    $errors = $validator->validate($this->body);
    
    if (!empty($errors)) {
      $this->sendErrorResponse($errors);
    }

    $command = new BookCommand($this->body);
    $book = $this->bookService->create($command);

    $this->sendSuccessResponse(['book' => $book], 'Book created successfully', 201);
  }

  public function updateBook(int $id)
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
      $this->sendErrorResponse(['message' => 'Failed to update book'], 500);
      return;
    }

    $this->sendSuccessResponse(['book' => $updatedBook], 'Book updated successfully');
  }
}
