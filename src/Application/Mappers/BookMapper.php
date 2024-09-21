<?php

namespace App\Application\Mappers;

use App\Application\Dtos\BookDto;

class BookMapper
{
  public static function mapOne(array|null $data): BookDto|null
  {
    if (!$data) return null;
    
    $autores = !empty($data['autores']) ? explode(',', $data['autores']) : [];
    $assuntos = !empty($data['assuntos']) ? explode(',', $data['assuntos']) : [];

    return new BookDto(
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
}
