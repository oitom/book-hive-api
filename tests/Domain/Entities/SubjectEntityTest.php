<?php

namespace Tests\App\Domain\Entities;

use App\Domain\Entities\SubjectEntity;
use PHPUnit\Framework\TestCase;

class SubjectEntityTest extends TestCase
{
    public function testCreateSubjectEntity(): void
    {
        $descricao = 'Matemática';
        $subject = new SubjectEntity($descricao);

        // Verifica se a descrição está sendo atribuída corretamente
        $this->assertEquals($descricao, $subject->getDescricao());
    }

    public function testSetDescricao(): void
    {
        $subject = new SubjectEntity('Ciências');

        // Atualiza a descrição do assunto
        $novaDescricao = 'Biologia';
        $subject->setDescricao($novaDescricao);

        // Verifica se a descrição foi atualizada corretamente
        $this->assertEquals($novaDescricao, $subject->getDescricao());
    }
}
