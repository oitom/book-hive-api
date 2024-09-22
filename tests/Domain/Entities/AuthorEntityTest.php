<?php

namespace Tests\App\Domain\Entities;

use App\Domain\Entities\AuthorEntity;
use PHPUnit\Framework\TestCase;

class AuthorEntityTest extends TestCase
{
    public function testCreateAuthorEntity(): void
    {
        $authorName = 'Autor Teste';
        $author = new AuthorEntity($authorName);

        // Verifica se o nome está sendo atribuído corretamente
        $this->assertEquals($authorName, $author->getNome());
    }

    public function testSetNome(): void
    {
        $author = new AuthorEntity('Autor Original');

        // Atualiza o nome do autor
        $newAuthorName = 'Autor Atualizado';
        $author->setNome($newAuthorName);

        // Verifica se o nome foi atualizado corretamente
        $this->assertEquals($newAuthorName, $author->getNome());
    }
}
