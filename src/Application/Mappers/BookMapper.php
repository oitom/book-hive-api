<?php

namespace App\Application\Mappers;

use App\Application\Dtos\BookDto;
use App\Domain\Entity\Book;
use App\Domain\Entity\Author;
use App\Domain\Entity\Subject;

class BookMapper
{
  public static function mapOne(array|null $data): BookDto|null
  {
    if (!$data) return null;
    
    $autores = !empty($data['autores']) ? explode(',', $data['autores']) : [];
    $assuntos = !empty($data['assuntos']) ? explode(',', $data['assuntos']) : [];

    return new BookDto(
      $data['id'],
      $data['titulo'],
      $data['editora'],
      $data['edicao'],
      (int)$data['anoPublicacao'],
      (float)$data['preco'],
      $autores,
      $assuntos
    );
  }

  public static function mapList(array|null $dataList): array|null
  {
    return array_map(function ($data): BookDto|null {
      return self::mapOne($data);
    }, $dataList);
  }

  public static function toEntity(BookDto $bookDto): Book
  {
    $autores = array_map(function ($nome) {
      return new Author($nome);
    }, $bookDto->autores);

    $assuntos = array_map(function ($descricao) {
      return new Subject($descricao);
    }, $bookDto->assuntos);

    return new Book(
        $bookDto->titulo,
        $bookDto->editora,
        $bookDto->edicao,
        $bookDto->anoPublicacao,
        $bookDto->preco,
        $autores,
        $assuntos
    );
  }
}
