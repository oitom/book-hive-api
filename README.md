# book-hive-api
API CRUD Livros.

[![CodeFactor](https://www.codefactor.io/repository/github/oitom/book-hive-api/badge)](https://www.codefactor.io/repository/github/oitom/book-hive-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/b6622d01eb5db20fb80e/maintainability)](https://codeclimate.com/github/oitom/book-hive-api/maintainability)
[![codecov](https://codecov.io/github/oitom/book-hive-api/graph/badge.svg?token=fEo3raVrPp)](https://codecov.io/github/oitom/book-hive-api)

## Solução
Este projeto foi desenvolvido...

Além disso, a integridade do código foi assegurada por meio de testes unitários implementados com PHPUnit.

### Estrutura do projeto
```

```

## Configuração do Ambiente
Pré-requisitos

- Docker
- Composer

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

4. Rode o arquivo `migration.sql` no client do BD

5. Acesse a aplicação em http://localhost:8080.

## Execução de Testes com PHPUnit

1. Gerando um Relatório de Cobertura de Testes com PHPUnit:
```
docker exec -it book-hive-api vendor/bin/phpunit --coverage-html=coverage/
```
2. Se tudo der certo, o relatório estará disponível em: http://localhost:8080/coverage/index.html

## Executando o Projeto
Para testar as chamadas da API, você pode seguir estas instruções utilizando ferramentas como Postman (no) ou curl. Certifique-se de incluir os cabeçalhos `client-id` e `client-secret` em todas as suas solicitações para autenticação adequada. Siga os passos abaixo:

#### Importando collection no postman
Se você escolher utilizar o Postman, pode importar facilmente os arquivos de collection e environment presentes na raiz do projeto. Para isso, siga estes passos simples:

1. Abra o Postman;
2. Clique em "Import" no canto superior esquerdo;
3. Selecione a opção "File" e escolha os arquivos de `collection` (*.json) e `environment` (*.json) que está na raiz do projeto;
4. Após a importação, os endpoints e variáveis necessárias estarão disponíveis para uso imediato.

#### Credenciais

- `client-id`: 
```
bookhive
```

- `client-secret`: 
```
550e8400-e29b-41d4-a716-446655440000
```

### Cenário de Sucesso

### Cenário de Erros

## Encerrando o Ambiente
Para encerrar o ambiente Docker, execute:

```
docker-compose down
```
Isso desligará o contêiner Docker e liberará os recursos.