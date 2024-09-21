<?php

namespace App\Presentation\Controller;
use App\Presentation\Validator\BookValidator;
use App\Application\Service\BookService;

class BookController extends BaseController
{
  private BookService $bookService;
  
  public function __construct(array $headers, array $body, array $queryParams)
  {
    $this->bookService = new BookService();
    parent::__construct($headers, $body, $queryParams);
  }
  
  public function listBooks()
  {
    return json_encode(['books' => ['Book 1', 'Book 2']]);
  }

  public function createBook()
  {
    $validator = new BookValidator();
    $errors = $validator->validate($this->body);
    
    if (!empty($errors)) {
      $this->sendErrorResponse($errors);
    }

    $validatedData = $this->body;
    $book = $this->bookService->create($validatedData);

    $this->sendSuccessResponse(['book' => $book], 'Book created successfully', 201);
  }
}
