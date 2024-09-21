<?php

namespace App\Domain\Entity;

use DateTime;

class Book
{
  private string $titulo;
  private string $editora;
  private int $edicao;
  private string $anoPublicacao;
  private float $preco;
  private array $autor;   // Array de autores
  private array $assunto; // Array de assuntos

  private int $ativo;
  private DateTime $createdAt;
  private ?DateTime $updatedAt;
  private ?DateTime $deletedAt;

  public function __construct(
    string $titulo,
    string $editora,
    int $edicao,
    string $anoPublicacao,
    float $preco,
    array $autor,
    array $assunto,
    int $ativo = 1
  ) {
    $this->titulo = $titulo;
    $this->editora = $editora;
    $this->edicao = $edicao;
    $this->anoPublicacao = $anoPublicacao;
    $this->preco = $preco;
    $this->autor = $autor;
    $this->assunto = $assunto;

    $this->ativo = $ativo;
    $this->createdAt = new DateTime();
    $this->updatedAt = null;
    $this->deletedAt = null;
  }

  public function getTitulo(): string
  {
    return $this->titulo;
  }

  public function setTitulo(string $titulo): void
  {
    $this->titulo = $titulo;
  }

  public function getEditora(): string
  {
    return $this->editora;
  }

  public function setEditora(string $editora): void
  {
    $this->editora = $editora;
  }

  public function getEdicao(): int
  {
    return $this->edicao;
  }

  public function setEdicao(int $edicao): void
  {
    $this->edicao = $edicao;
  }

  public function getAnoPublicacao(): string
  {
    return $this->anoPublicacao;
  }

  public function setAnoPublicacao(string $anoPublicacao): void
  {
    $this->anoPublicacao = $anoPublicacao;
  }

  public function getPreco(): float
  {
    return $this->preco;
  }

  public function setPreco(float $preco): void
  {
    $this->preco = $preco;
  }

  public function getAutor(): array
  {
    return $this->autor;
  }

  public function setAutor(array $autor): void
  {
    $this->autor = $autor;
  }

  public function getAssunto(): array
  {
    return $this->assunto;
  }

  public function setAssunto(array $assunto): void
  {
    $this->assunto = $assunto;
  }

  public function setAtivo(int $ativo): void
  {
    $this->ativo = $ativo;
  }
  public function getAtivo(): int
  {
    return $this->ativo;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt->format('Y-m-d H:i:s');
  }

  public function getUpdatedAt(): ?string
  {
    return $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null;
  }

  public function setUpdatedAt(): void
  {
    $this->updatedAt = new DateTime();
  }

  public function getDeletedAt(): ?string
  {
    return $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null;
  }

  public function setDeletedAt(): void
  {
      $this->deletedAt = new DateTime();
  }
}
