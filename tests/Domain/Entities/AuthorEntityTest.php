<?php

namespace Tests\App\Domain\Entities;

use App\Domain\Entities\AuthorEntity;
use PHPUnit\Framework\TestCase;

class AuthorEntityTest extends TestCase
{
  public function testCreateAuthorEntity() : void
  {
    $authorName = 'Autor Teste';
    $author = new AuthorEntity($authorName);

    $this->assertEquals($authorName, $author->getNome());
  }

  public function testSetNome() : void
  {
    $author = new AuthorEntity('Autor Original');

    $newAuthorName = 'Autor Atualizado';
    $author->setNome($newAuthorName);

    $this->assertEquals($newAuthorName, $author->getNome());
  }
}
