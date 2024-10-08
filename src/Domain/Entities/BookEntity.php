<?php

namespace App\Domain\Entities;

use DateTime;

class BookEntity
{
  private string $titulo;
  private string $editora;
  private int $edicao;
  private string $anoPublicacao;
  private float $preco;
  private array $autores;
  private array $assuntos;
  private DateTime $createdAt;
  private ?DateTime $updatedAt;
  private ?DateTime $deletedAt;

  public function __construct(
    string $titulo,
    string $editora,
    int $edicao,
    string $anoPublicacao,
    float $preco,
    array $autores,
    array $assuntos,
    DateTime $createdAt = null,
    DateTime $updatedAt = null,
    DateTime $deletedAt = null
  ) {
    $this->titulo = $titulo;
    $this->editora = $editora;
    $this->edicao = $edicao;
    $this->anoPublicacao = $anoPublicacao;
    $this->preco = $preco;
    $this->autores = $autores;
    $this->assuntos = $assuntos;

    $this->createdAt = $createdAt ?? new DateTime();
    $this->updatedAt = $updatedAt;
    $this->deletedAt = $deletedAt;
  }

  public function getTitulo() : string
  {
    return $this->titulo;
  }

  public function setTitulo(string $titulo) : void
  {
    $this->titulo = $titulo;
  }

  public function getEditora() : string
  {
    return $this->editora;
  }

  public function setEditora(string $editora) : void
  {
    $this->editora = $editora;
  }

  public function getEdicao() : int
  {
    return $this->edicao;
  }

  public function setEdicao(int $edicao) : void
  {
    $this->edicao = $edicao;
  }

  public function getAnoPublicacao() : string
  {
    return $this->anoPublicacao;
  }

  public function setAnoPublicacao(string $anoPublicacao) : void
  {
    $this->anoPublicacao = $anoPublicacao;
  }

  public function getPreco() : float
  {
    return $this->preco;
  }

  public function setPreco(float $preco) : void
  {
    $this->preco = $preco;
  }

  public function getAutores() : array
  {
    return $this->autores;
  }

  public function setAutores(array $autores) : void
  {
    $this->autores = $autores;
  }

  public function getAssuntos() : array
  {
    return $this->assuntos;
  }

  public function setAssuntos(array $assuntos) : void
  {
    $this->assuntos = $assuntos;
  }

  public function getCreatedAt() : string
  {
    return $this->createdAt->format('Y-m-d H:i:s');
  }

  public function getUpdatedAt() : ?string
  {
    return $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null;
  }

  public function setUpdatedAt() : void
  {
    $this->updatedAt = new DateTime();
  }

  public function getDeletedAt() : ?string
  {
    return $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null;
  }

  public function setDeletedAt() : void
  {
    $this->deletedAt = new DateTime();
  }
}
