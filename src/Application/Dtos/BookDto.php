<?php

namespace App\Application\Dtos;
Use DateTime;

class BookDto
{
  public string $id;
  public string $titulo;
  public string $editora;
  public int $edicao;
  public int $anoPublicacao;
  public float $preco;
  public array $autores;
  public array $assuntos;
  public function __construct(
    string $id,
    string $titulo,
    string $editora,
    int $edicao,
    int $anoPublicacao,
    float $preco,
    array $autores,
    array $assuntos,

  ) {
    $this->id = $id;
    $this->titulo = $titulo;
    $this->editora = $editora;
    $this->edicao = $edicao;
    $this->anoPublicacao = $anoPublicacao;
    $this->preco = $preco;
    $this->autores = $autores;
    $this->assuntos = $assuntos;

  }
}
