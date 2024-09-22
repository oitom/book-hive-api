<?php

namespace Tests\App\Application\Mappers;

use App\Application\Mappers\BookMapper;
use App\Application\Dtos\BookDto;
use App\Domain\Entities\BookEntity;
use App\Domain\Entities\AuthorEntity;
use App\Domain\Entities\SubjectEntity;
use PHPUnit\Framework\TestCase;

class BookMapperTest extends TestCase
{
    public function testMapOne(): void
    {
        $data = [
            'id' => 1,
            'titulo' => 'Test Book',
            'editora' => 'Test Publisher',
            'edicao' => 1,
            'anoPublicacao' => 2021,
            'preco' => 29.99,
            'autores' => 'Author 1,Author 2',
            'assuntos' => 'Subject 1,Subject 2'
        ];

        $bookDto = BookMapper::mapOne($data);

        $this->assertInstanceOf(BookDto::class, $bookDto);
        $this->assertEquals(1, $bookDto->id);
        $this->assertEquals('Test Book', $bookDto->titulo);
        $this->assertEquals('Test Publisher', $bookDto->editora);
        $this->assertEquals(1, $bookDto->edicao);
        $this->assertEquals(2021, $bookDto->anoPublicacao);
        $this->assertEquals(29.99, $bookDto->preco);
        $this->assertEquals(['Author 1', 'Author 2'], $bookDto->autores);
        $this->assertEquals(['Subject 1', 'Subject 2'], $bookDto->assuntos);
    }

    public function testMapOneNull(): void
    {
        $bookDto = BookMapper::mapOne(null);
        $this->assertNull($bookDto);
    }

    public function testMapList(): void
    {
        $dataList = [
            'books' => [
                [
                    'id' => 1,
                    'titulo' => 'Test Book 1',
                    'editora' => 'Publisher 1',
                    'edicao' => 1,
                    'anoPublicacao' => 2020,
                    'preco' => 19.99,
                    'autores' => 'Author A',
                    'assuntos' => 'Subject A'
                ],
                [
                    'id' => 2,
                    'titulo' => 'Test Book 2',
                    'editora' => 'Publisher 2',
                    'edicao' => 2,
                    'anoPublicacao' => 2021,
                    'preco' => 29.99,
                    'autores' => 'Author B',
                    'assuntos' => 'Subject B'
                ]
            ],
            'pagination' => [
                'total' => 2,
                'currentPage' => 1,
                'totalPages' => 1
            ]
        ];

        $result = BookMapper::mapList($dataList);

        $this->assertIsArray($result);
        $this->assertCount(2, $result['books']);
        $this->assertInstanceOf(BookDto::class, $result['books'][0]);
        $this->assertInstanceOf(BookDto::class, $result['books'][1]);
        $this->assertEquals($dataList['pagination'], $result['pagination']);
    }

    public function testToEntity(): void
    {
        $bookDto = new BookDto(
            1,
            'Test Book',
            'Test Publisher',
            1,
            2021,
            29.99,
            ['Author 1'],
            ['Subject 1']
        );

        $bookEntity = BookMapper::toEntity($bookDto);

        $this->assertInstanceOf(BookEntity::class, $bookEntity);
        $this->assertEquals('Test Book', $bookEntity->getTitulo());
        $this->assertEquals('Test Publisher', $bookEntity->getEditora());
        $this->assertEquals(1, $bookEntity->getEdicao());
        $this->assertEquals(2021, $bookEntity->getAnoPublicacao());
        $this->assertEquals(29.99, $bookEntity->getPreco());
        $this->assertCount(1, $bookEntity->getAutores());
        $this->assertInstanceOf(AuthorEntity::class, $bookEntity->getAutores()[0]);
        $this->assertCount(1, $bookEntity->getAssuntos());
        $this->assertInstanceOf(SubjectEntity::class, $bookEntity->getAssuntos()[0]);
    }

    public function testToEntityWithTimestamps(): void
    {
        $bookDto = new BookDto(
            1,
            'Test Book',
            'Test Publisher',
            1,
            2021,
            29.99,
            ['Author 1'],
            ['Subject 1']
        );

        $bookEntity = BookMapper::toEntity($bookDto);

        $this->assertInstanceOf(BookEntity::class, $bookEntity);
        $this->assertEquals('Test Book', $bookEntity->getTitulo());
        $this->assertEquals('Test Publisher', $bookEntity->getEditora());
        $this->assertEquals(1, $bookEntity->getEdicao());
        $this->assertEquals(2021, $bookEntity->getAnoPublicacao());
        $this->assertEquals(29.99, $bookEntity->getPreco());
        $this->assertCount(1, $bookEntity->getAutores());
        $this->assertInstanceOf(AuthorEntity::class, $bookEntity->getAutores()[0]);
        $this->assertCount(1, $bookEntity->getAssuntos());
        $this->assertInstanceOf(SubjectEntity::class, $bookEntity->getAssuntos()[0]);

        $this->assertNotNull($bookEntity->getCreatedAt());
        $this->assertNull($bookEntity->getUpdatedAt());
        $this->assertNull($bookEntity->getDeletedAt());
}
}
