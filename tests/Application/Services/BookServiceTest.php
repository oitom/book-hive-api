<?php

namespace Tests\App\Application\Services;

use App\Application\Services\BookService;
use App\Domain\Commands\BookCommand;
use App\Domain\Repositories\BookRepositoryInterface;
use App\Domain\Repositories\CacheInterface;
use App\Domain\Entities\AuthorEntity;
use App\Domain\Entities\SubjectEntity;
use App\Domain\Entities\BookEntity;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
{
    private BookService $bookService;
    private $bookRepositoryMock;
    private $cacheMock;

    protected function setUp(): void
    {
        $this->bookRepositoryMock = $this->createMock(BookRepositoryInterface::class);
        $this->cacheMock = $this->createMock(CacheInterface::class);
        
        // Cria a instância do serviço
        $this->bookService = new BookService($this->bookRepositoryMock, $this->cacheMock);
    }

    public function testCreateBookReturnsTrue()
    {
        $bookData = [
            'titulo' => 'Título do Livro',
            'editora' => 'Editora Exemplo',
            'edicao' => 1,
            'anoPublicacao' => 2023,
            'preco' => 49.90,
            'autores' => [['nome' => 'Autor 1']],
            'assuntos' => [['descricao' => 'Assunto 1']]
        ];
        $bookCommand = new BookCommand($bookData);

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $this->cacheMock
            ->expects($this->once())
            ->method('clear')
            ->with('books_search_*');

        $result = $this->bookService->create($bookCommand);

        $this->assertTrue($result);
    }

    public function testUpdateBookReturnsTrue()
    {
        $id = 1;
        $bookData = [
            'titulo' => 'Título Atualizado',
            'editora' => 'Editora Atualizada',
            'edicao' => 1,
            'anoPublicacao' => 2024,
            'preco' => 59.90,
            'autores' => [['nome' => 'Autor Atualizado']],
            'assuntos' => [['descricao' => 'Assunto Atualizado']]
        ];
        $bookCommand = new BookCommand($bookData);

        // Adicione a chave "id" ao array
        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn([
                'id' => $id,  // Adicione isso
                'titulo' => 'Título Original',
                'editora' => 'Editora Original',
                'edicao' => 1,
                'anoPublicacao' => 2023,
                'preco' => 49.90,
                'autores' => [],
                'assuntos' => []
            ]);

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with($id, $this->isInstanceOf(BookEntity::class))
            ->willReturn(true);

        $this->cacheMock
            ->expects($this->once())
            ->method('delete')
            ->with("book_{$id}");
        
        $this->cacheMock
            ->expects($this->once())
            ->method('clear')
            ->with('books_search_*');

        $result = $this->bookService->update($id, $bookCommand);

        $this->assertTrue($result);
    }

    public function testDeleteBookReturnsTrue()
    {
        $id = 1;
    
        // Adicione a chave "id" ao array
        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn([
                'id' => $id,  // Adicione isso
                'titulo' => 'Título',
                'editora' => 'Editora',
                'edicao' => 1,
                'anoPublicacao' => 2023,
                'preco' => 49.90,
                'autores' => [],
                'assuntos' => []
            ]);
    
        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($id, $this->isInstanceOf(BookEntity::class))
            ->willReturn(true);
    
        $this->cacheMock
            ->expects($this->once())
            ->method('delete')
            ->with("book_{$id}");
    
        $this->cacheMock
            ->expects($this->once())
            ->method('clear')
            ->with('books_search_*');
    
        $result = $this->bookService->delete($id);
    
        $this->assertTrue($result);
    }

    public function testCreateBookReturnsFalseWhenSaveFails()
    {
        $bookData = [
            'titulo' => 'Título do Livro',
            'editora' => 'Editora Exemplo',
            'edicao' => 1,
            'anoPublicacao' => 2023,
            'preco' => 49.90,
            'autores' => [['nome' => 'Autor 1']],
            'assuntos' => [['descricao' => 'Assunto 1']]
        ];
        $bookCommand = new BookCommand($bookData);

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $result = $this->bookService->create($bookCommand);

        $this->assertFalse($result);
    }

    public function testUpdateBookReturnsNullWhenBookNotFound()
    {
        $id = 1;
        $bookData = [
            'titulo' => 'Título Atualizado',
            'editora' => 'Editora Atualizada',
            'edicao' => 2,
            'anoPublicacao' => 2024,
            'preco' => 59.90,
            'autores' => [['nome' => 'Autor Atualizado']],
            'assuntos' => [['descricao' => 'Assunto Atualizado']]
        ];
        $bookCommand = new BookCommand($bookData);

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $result = $this->bookService->update($id, $bookCommand);

        $this->assertNull($result);
    }

    public function testDeleteBookReturnsFalseWhenBookNotFound()
    {
        $id = 1;

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $result = $this->bookService->delete($id);

        $this->assertFalse($result);
    }
}
