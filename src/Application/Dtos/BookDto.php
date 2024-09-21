<?php

namespace App\Application\Dtos;

class BookDto
{
  public string $titulo;
  public string $editora;
  public string $edicao;
  public int $anoPublicacao;
  public float $preco;
  public array $autores;
  public array $assuntos;

  public function __construct(
    string $titulo,
    string $editora,
    string $edicao,
    int $anoPublicacao,
    float $preco,
    array $autores,
    array $assuntos
  ) {
    $this->titulo = $titulo;
    $this->editora = $editora;
    $this->edicao = $edicao;
    $this->anoPublicacao = $anoPublicacao;
    $this->preco = $preco;
    $this->autores = $autores;
    $this->assuntos = $assuntos;
  }
}
