<?php

namespace App\Presentation\Controller;

class BookController
{
  public function listBooks()
  {
    return json_encode(['books' => ['Book 1', 'Book 2']]);
  }

  public function createBook()
  {    
    return json_encode(['message' => 'Book created successfully']);
  }
}
