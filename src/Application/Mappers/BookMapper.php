<?php

namespace App\Application\Mappers;

use App\Application\Dtos\BookDto;
use App\Domain\Entities\BookEntity;
use App\Domain\Entities\AuthorEntity;
use App\Domain\Entities\SubjectEntity;

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
    $books = array_map(function ($data): BookDto|null {
      return self::mapOne($data);
    }, $dataList['books'] ?? []);

    return [
      'books' => $books,
      'pagination' => $dataList['pagination'] ?? null
    ];
  }

  public static function toEntity(BookDto $bookDto): BookEntity
  {
    $autores = array_map(function ($nome) {
      return new AuthorEntity($nome);
    }, $bookDto->autores);

    $assuntos = array_map(function ($descricao) {
      return new SubjectEntity($descricao);
    }, $bookDto->assuntos);

    return new BookEntity(
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
