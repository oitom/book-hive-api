<?php

namespace App\Presentation\Validators;

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
      'autores' => ['required', 1, 'array'],
      'assuntos' => ['required', 1, 'array'],
    ];

    parent::__construct($rules);
  }
}
