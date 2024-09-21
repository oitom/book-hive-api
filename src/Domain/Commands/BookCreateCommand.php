<?php

namespace App\Domain\Commands;

class BookCreateCommand
{
  public string $titulo;
  public string $editora;
  public string $edicao;
  public int $anoPublicacao;
  public float $preco;
  public array $autores;
  public array $assuntos;

  public function __construct(array $book
  ) {
    $this->titulo = $book['titulo'];
    $this->editora = $book['editora'];
    $this->edicao = $book['edicao'];
    $this->anoPublicacao = $book['anoPublicacao'];
    $this->preco = $book['preco'];
    $this->autores = $book['autores'];
    $this->assuntos = $book['assuntos'];
  }
}
