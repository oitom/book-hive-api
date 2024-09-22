<?php

namespace Tests\App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\SubjectRepository;
use App\Domain\Entities\SubjectEntity;
use PHPUnit\Framework\TestCase;

class SubjectRepositoryTest extends TestCase
{
    private $connectionMock;
    private SubjectRepository $subjectRepository;

    protected function setUp(): void
    {
        parent::setUp();
        // Criar um mock do PDO
        $this->connectionMock = $this->createMock(\PDO::class);
        $this->subjectRepository = new SubjectRepository($this->connectionMock);
    }

    public function testSaveAllInsertsSubjects(): void
    {
        $bookId = 1;
        $assuntos = [
            new SubjectEntity('Subject 1'),
            new SubjectEntity('Subject 2'),
        ];

        // Configurar o mock para esperar a chamada do prepare e execute
        $stmtMock = $this->createMock(\PDOStatement::class);
        $this->connectionMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->exactly(2))->method('execute')->willReturn(true);

        // Chamar o método
        $this->subjectRepository->saveAll($assuntos, $bookId);
    }

    public function testDeleteAllByBookIdDeletesSubjects(): void
    {
        $bookId = 1;

        // Configurar o mock para esperar a chamada do prepare e execute
        $stmtMock = $this->createMock(\PDOStatement::class);
        $this->connectionMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('bindParam')->with(':bookId', $bookId, \PDO::PARAM_INT);
        $stmtMock->expects($this->once())->method('execute')->willReturn(true);

        // Chamar o método
        $this->subjectRepository->deleteAllByBookId($bookId);
    }
}
