<?php

namespace App\Domain\Commands;

class BookCommand
{
  public string $titulo;
  public string $editora;
  public string $edicao;
  public int $anoPublicacao;
  public float $preco;
  public array $autores;
  public array $assuntos;

  public function __construct(array $book) {
    $this->titulo = $book['titulo'] ??  null;
    $this->editora = $book['editora'] ??  null;
    $this->edicao = $book['edicao'] ??  null;
    $this->anoPublicacao = $book['anoPublicacao'] ??  null;
    $this->preco = $book['preco'] ??  null;
    $this->autores = $book['autores'] ??  null;
    $this->assuntos = $book['assuntos'] ??  null;
  }
}
