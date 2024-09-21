<?php

namespace App\Domain\Entity;

class Subject
{
  private string $descricao;

  public function __construct(string $descricao)
  {
    $this->descricao = $descricao;
  }

  public function getDescricao(): string
  {
    return $this->descricao;
  }

  public function setDescricao(string $descricao): void
  {
    $this->descricao = $descricao;
  }
}
