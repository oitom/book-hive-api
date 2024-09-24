# BookHiveAPI
API CRUD Livros.

[![CodeFactor](https://www.codefactor.io/repository/github/oitom/book-hive-api/badge)](https://www.codefactor.io/repository/github/oitom/book-hive-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/b6622d01eb5db20fb80e/maintainability)](https://codeclimate.com/github/oitom/book-hive-api/maintainability)
[![Workflow](https://github.com/oitom/book-hive-api/actions/workflows/ci.yml/badge.svg)](https://github.com/oitom/book-hive-api/actions/workflows/ci.yml)
[![codecov](https://codecov.io/github/oitom/book-hive-api/graph/badge.svg?token=fEo3raVrPp)](https://codecov.io/github/oitom/book-hive-api)

`PHP 8.2` `PHP Unit 9.6` `TCPDF 6.7`
## Solução
Este projeto foi desenvolvido em PHP 8, seguindo os princípios da arquitetura RESTful para a construção de APIs.
Além disso, a integridade do código foi assegurada por meio de testes unitários implementados com PHPUnit.

### Estrutura do projeto
```
/project
│
├── index.php
├── src
│   └── Application
│       └── Dtos
│       └── Mappers
│       └── Services
│   └── Domain
│       └── Commands
│       └── Entities
│       └── Repositories
│   └── Infrastructure
│       └── Database
│       └── Repositories
│   └── Presentation
│       └── Controllers
│       └── Routers
│       └── Validators
```

## Configuração do Ambiente
Pré-requisitos

- Docker

## Instalação
1. Clone este repositório:

```
git clone https://github.com/oitom/book-hive-api
```

2. Acesse o diretório:
```
cd book-hive-api
```

3. Inicie o contêiner Docker:
```
docker-compose up -d --build
```

4. Rode o arquivo `migration.sql` no seu client BD. As credenciais do BD estão disponíveis no arquivo `.env`.

5. Acesse a aplicação em http://localhost:8080.

## Execução de Testes com PHPUnit

1. Gerando um Relatório de Cobertura de Testes com PHPUnit:
```
docker exec -it book-hive-api vendor/bin/phpunit --coverage-html=coverage/
```

2. Se tudo der certo, o relatório de cobertura estará disponível em: [coverage](http://localhost:8080/coverage/index.html)

## Executando o Projeto
Você pode testar o projeto utilizando ferramentas como cURL, Postman ou qualquer outro cliente HTTP de sua preferência. 
Seguem algumas sugestões de uso:

#### Importando collection no postman
Se você escolher utilizar o Postman, pode importar facilmente os arquivos de collection e environment presentes na raiz do projeto. Para isso, siga estes passos simples:

1. Abra o Postman;
2. Clique em "Import" no canto superior esquerdo;
3. Selecione a opção "File" e escolha o arquivo de `collection` (*.json)  que está no diretório `/postman` na raiz do projeto;
4. Após a importação, os endpoints e variáveis necessárias estarão disponíveis para uso imediato.

### Requests

#### Criação de livro:
```bash
curl --location 'localhost:8080/books' \
--header 'Authorization: 1234' \
--header 'Content-Type: application/json' \
--data '{
  "titulo": "A Game of Thrones",
  "editora": "Bantam Books",
  "edicao": 1,
  "anoPublicacao": "1996",
  "preco": 49.90,
  "autores": [
    {
      "nome": "George R. R. Martin"
    }
  ],
  "assuntos": [
    {
      "descricao": "Fantasia"
    },
    {
      "descricao": "Épico"
    },
  ]
}
'
```
#### Atualização de livro:
```bash
curl --location --request PUT 'localhost:8080/books/8' \
--header 'Authorization: 1234' \
--header 'Content-Type: application/json' \
--data '{
    "titulo": "Introduction to Quantum Computing",
    "editora": "FutureTech",
    "edicao": 1,
    "anoPublicacao": 2020,
    "preco": 59.99,
    "autores": [
        {
          "nome": "Alice Johnson"
        },
        {
          "nome": "David Clark"
        }
    ],
    "assuntos": [
        {
          "descricao": "APIs"
        },
        {
          "descricao": "Programming"
        }
    ]  
}'
```
#### Remoção de livro:
```bash
curl --location --request DELETE 'localhost:8080/books/8'
```
#### Listagem de livro por id:
```bash
curl --location 'localhost:8080/books/8'
```
#### Listagem de livros:
```bash
curl --location 'localhost:8080/books?search=&page=1&pageSize=10'
```

## Encerrando o Ambiente
Para encerrar o ambiente Docker, execute:

```
docker-compose down
```
Isso desligará o contêiner Docker e liberará os recursos.

## Melhorias e Desenvolvimentos futuros
### Evolução da Autenticação
Planeja-se aprimorar o sistema de autenticação, adicionando suporte para OAuth 2.0 e JWT (JSON Web Token), garantindo uma autenticação segura e escalável. Isso permitirá a integração com provedores de autenticação externos, como Google, Facebook e GitHub, além de possibilitar um controle de acesso mais robusto e refinado para diferentes usuários dentro da aplicação.

### Criação do Módulo de Usuário
Outro passo importante será a criação de um módulo dedicado ao gerenciamento de usuários. Esse módulo incluirá o CRUD completo para perfis de usuário, com funcionalidades como:

- Cadastro e edição de perfis,
- Gerenciamento de permissões,
- Recuperação de senha,
- Auditoria de ações dos usuários no sistema.

### Integrações com APIs de Pesquisa de Livros

Para enriquecer a experiência do usuário, pretendemos integrar a aplicação com APIs de pesquisa de livros, como a Google Books API ou Open Library API. Com essa integração, os usuários poderão pesquisar e importar informações detalhadas sobre livros diretamente na plataforma, além de obter dados atualizados como resenhas, capas de livros e mais detalhes bibliográficos automaticamente.

*"A verdadeira sabedoria está em reconhecer o quão pouco sabemos e no desejo constante de aprender mais."* – Sócrates
