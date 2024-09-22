<?php

namespace Tests\App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\AuthorRepository;
use App\Domain\Entities\AuthorEntity;
use PHPUnit\Framework\TestCase;

class AuthorRepositoryTest extends TestCase
{
    private $connectionMock;
    private AuthorRepository $authorRepository;

    protected function setUp(): void
    {
        parent::setUp();
        // Criar um mock do PDO
        $this->connectionMock = $this->createMock(\PDO::class);
        $this->authorRepository = new AuthorRepository($this->connectionMock);
    }

    public function testSaveAllInsertsAuthors(): void
    {
        $bookId = 1;
        $autores = [
            new AuthorEntity('Author 1'),
            new AuthorEntity('Author 2'),
        ];

        // Configurar o mock para esperar a chamada do prepare e execute
        $stmtMock = $this->createMock(\PDOStatement::class);
        $this->connectionMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->exactly(2))->method('execute')->willReturn(true);

        // Chamar o método
        $this->authorRepository->saveAll($autores, $bookId);
    }

    public function testDeleteAllByBookIdDeletesAuthors(): void
    {
        $bookId = 1;

        // Configurar o mock para esperar a chamada do prepare e execute
        $stmtMock = $this->createMock(\PDOStatement::class);
        $this->connectionMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('bindParam')->with(':bookId', $bookId, \PDO::PARAM_INT);
        $stmtMock->expects($this->once())->method('execute')->willReturn(true);

        // Chamar o método
        $this->authorRepository->deleteAllByBookId($bookId);
    }
}
