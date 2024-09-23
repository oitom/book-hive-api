<?php

namespace Tests\App\Domain\Entities;

use App\Domain\Entities\SubjectEntity;
use PHPUnit\Framework\TestCase;

class SubjectEntityTest extends TestCase
{
  public function testCreateSubjectEntity() : void
  {
    $descricao = 'Matemática';
    $subject = new SubjectEntity($descricao);

    $this->assertEquals($descricao, $subject->getDescricao());
  }

  public function testSetDescricao() : void
  {
    $subject = new SubjectEntity('Ciências');
    $novaDescricao = 'Biologia';
    $subject->setDescricao($novaDescricao);

    $this->assertEquals($novaDescricao, $subject->getDescricao());
  }
}
