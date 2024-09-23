<?php

namespace Tests\Presentation\Validators;

use App\Presentation\Validators\BookValidator;
use PHPUnit\Framework\TestCase;

class BookValidatorTest extends TestCase
{
  protected BookValidator $validator;

  protected function setUp() : void
  {
    $this->validator = new BookValidator();
  }

  public function testValidateRequiredFields()
  {
    $data = [
      'titulo'        => '',
      'editora'       => '',
      'edicao'        => '',
      'anoPublicacao' => '',
      'preco'         => '',
      'autores'       => [],
      'assuntos'      => [],
    ];

    $errors = $this->validator->validate($data);

    $this->assertArrayHasKey('titulo', $errors);
    $this->assertArrayHasKey('editora', $errors);
    $this->assertArrayHasKey('edicao', $errors);
    $this->assertArrayHasKey('anoPublicacao', $errors);
    $this->assertArrayHasKey('preco', $errors);
    $this->assertArrayHasKey('autores', $errors);
    $this->assertArrayHasKey('assuntos', $errors);
  }

  public function testValidateStringLength()
  {
    $data = [
      'titulo'  => str_repeat('A', 41),
      'editora' => str_repeat('B', 41)
    ];

    $errors = $this->validator->validate($data);

    $this->assertArrayHasKey('titulo', $errors);
    $this->assertArrayHasKey('editora', $errors);
  }

  public function testValidateIntegerField()
  {
    $data = [
      'edicao' => 'not-an-integer',
    ];
    $errors = $this->validator->validate($data);

    $this->assertArrayHasKey('edicao', $errors);
  }

  public function testValidateYearField()
  {
    $data = [
      'anoPublicacao' => 'not-a-year',
    ];
    $errors = $this->validator->validate($data);

    $this->assertArrayHasKey('anoPublicacao', $errors);
  }

  public function testValidateDecimalField()
  {
    $data = [
      'preco' => 'not-a-decimal',
    ];
    $errors = $this->validator->validate($data);

    $this->assertArrayHasKey('preco', $errors);
  }

  public function testValidateArrayField()
  {
    $data = [
      'autores'  => [],
      'assuntos' => []
    ];
    $errors = $this->validator->validate($data);

    $this->assertArrayHasKey('autores', $errors);
    $this->assertArrayHasKey('assuntos', $errors);
  }

  public function testValidateSuccessful()
  {
    $data = [
      'titulo'        => 'Um Grande Livro',
      'editora'       => 'Editora Exemplo',
      'edicao'        => 1,
      'anoPublicacao' => '2021',
      'preco'         => 29.99,
      'autores'       => ['Autor 1'],
      'assuntos'      => ['Ficção'],
    ];
    $errors = $this->validator->validate($data);

    $this->assertEmpty($errors);
  }
}
