<?php

namespace App\Presentation\Validator;

class BookValidator extends BaseValidator
{
  public function __construct()
  {
    $rules = [
      'titulo' => ['required', 40, 'string'],
      'editora' => ['required', 40, 'string'],
      'edicao' => ['required', 11, 'integer'],
      'anoPublicacao' => ['required', 4, 'string'],
      'preco' => ['required', 11, 'decimal'],
      'autor' => ['required', 1, 'array'],
      'assunto' => ['required', 1, 'array'],
    ];

    parent::__construct($rules);
  }
}
