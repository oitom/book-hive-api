<?php

namespace Tests\App\Infrastructure\Repositories;

use App\Domain\Entities\BookEntity;
use App\Infrastructure\Repositories\BookRepository;
use App\Infrastructure\Repositories\AuthorRepository;
use App\Infrastructure\Repositories\SubjectRepository;
use App\Infrastructure\Database\PDOConnection;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class BookRepositoryTest extends TestCase
{
    private $connectionMock;
    private $bookRepository;
    private $authorRepositoryMock;
    private $subjectRepositoryMock;
    private $stmtMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar mock do PDO
        $this->connectionMock = $this->createMock(PDO::class);
        
        // Criar mock do PDOStatement
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Configurar o mock do prepare para retornar o stmtMock
        $this->connectionMock->method('prepare')->willReturn($this->stmtMock);

        // Criar instância do repositório
        $this->bookRepository = new BookRepository();
        $this->bookRepository->setConnection($this->connectionMock);

        // Mockar as dependências
        $this->authorRepositoryMock = $this->createMock(AuthorRepository::class);
        $this->subjectRepositoryMock = $this->createMock(SubjectRepository::class);

        // Substituir as dependências reais por mocks
        $this->bookRepository->setAuthorRepository($this->authorRepositoryMock);
        $this->bookRepository->setSubjectRepository($this->subjectRepositoryMock);
    }

    public function testSaveBook(): void
    {
        $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);

        // Configurar o comportamento do stmtMock para o método save
        $this->stmtMock->expects($this->any())
            ->method('execute')
            ->willReturn(true); // Simula uma execução bem-sucedida

        // Esperar que os métodos de saveAll sejam chamados
        $this->authorRepositoryMock
            ->expects($this->once())
            ->method('saveAll')
            ->with($this->anything(), $this->anything());

        $this->subjectRepositoryMock
            ->expects($this->once())
            ->method('saveAll')
            ->with($this->anything(), $this->anything());

        $result = $this->bookRepository->save($book);
        $this->assertTrue($result);
    }

    public function testSaveBookThrowsException(): void
    {
        $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);

        // Configurar o comportamento do stmtMock para simular uma exceção
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \Exception('Database error')));

        $result = $this->bookRepository->save($book);
        $this->assertFalse($result);
    }

    public function testFindOneReturnsBook(): void
    {
        $id = 1;

        // Configurar o comportamento do stmtMock para o método findOne
        $this->stmtMock->method('fetch')->willReturn([
            'id' => $id,
            'titulo' => 'Test Book',
            'editora' => 'Test Publisher',
            'edicao' => 1,
            'anoPublicacao' => 2023,
            'preco' => 29.99,
        ]);

        $result = $this->bookRepository->findOne($id);
        $this->assertIsArray($result);
        $this->assertEquals($id, $result['id']);
    }

    public function testFindReturnsBooks(): void
    {
        // Configurar o comportamento do stmtMock para o método find
        $this->stmtMock->method('fetchAll')->willReturn([[ 
            'id' => 1,
            'titulo' => 'Test Book',
            'editora' => 'Test Publisher',
            'edicao' => 1,
            'anoPublicacao' => 2023,
            'preco' => 29.99,
        ]]);

        $result = $this->bookRepository->find('', 10, 0);
        $this->assertArrayHasKey('books', $result);
        $this->assertNotEmpty($result['books']);
    }

    public function testUpdateBook(): void
    {
        $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);
        $id = 1;

        // Configurar o comportamento do stmtMock para o método update
        $this->stmtMock->expects($this->any())
            ->method('execute')
            ->willReturn(true); // Simula uma execução bem-sucedida

        // Esperar que os métodos de deleteAllByBookId e saveAll sejam chamados
        $this->authorRepositoryMock
            ->expects($this->once())
            ->method('deleteAllByBookId')
            ->with($id);
        
        $this->authorRepositoryMock
            ->expects($this->once())
            ->method('saveAll')
            ->with($this->anything(), $id);

        $this->subjectRepositoryMock
            ->expects($this->once())
            ->method('deleteAllByBookId')
            ->with($id);
        
        $this->subjectRepositoryMock
            ->expects($this->once())
            ->method('saveAll')
            ->with($this->anything(), $id);

        $result = $this->bookRepository->update($id, $book);
        $this->assertTrue($result);
    }

    public function testDeleteBook(): void
    {
        $id = 1;
        $book = new BookEntity("Titulo", "Editora", 1, "2024", 29.90, [], []);

        // Configurar o comportamento do stmtMock para o método delete
        $this->stmtMock->expects($this->any())
            ->method('execute')
            ->willReturn(true); // Simula uma execução bem-sucedida

        $result = $this->bookRepository->delete($id, $book);
        $this->assertTrue($result);
    }
}
