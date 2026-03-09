# GalaxyFlix

GalaxyFlix é uma aplicação web desenvolvida em Laravel para gerenciamento de um catálogo de filmes. O sistema permite cadastrar, listar, editar e excluir filmes, além de integrar automaticamente informações adicionais a partir da API do The Movie Database (TMDB), como poster, sinopse e nota de avaliação.

## Descrição

O objetivo do GalaxyFlix é oferecer uma interface simples para organização de filmes em um banco de dados, enriquecendo os registros com dados externos obtidos por meio da API TMDB. Ao cadastrar um filme informando título, gênero e ano, o sistema realiza uma requisição à API para obter informações adicionais e armazená-las automaticamente.

## Funcionalidades

* Cadastro de filmes
* Listagem de filmes com paginação
* Busca por título, gênero ou ano
* Edição de filmes cadastrados
* Exclusão de filmes
* Integração com API externa para obter poster, sinopse e nota

## Tecnologias Utilizadas

* Laravel 12 (framework PHP)
* PHP 8+
* MySQL
* Blade (sistema de templates do Laravel)
* API The Movie Database (TMDB)
* CSS utilitário via CDN

## Requisitos

Antes de executar o projeto, é necessário possuir:

* PHP 8.1 ou superior
* Composer
* MySQL
* Git
* Navegador web

## Instalação

1. Clone o repositório:

```
git clone https://github.com/seu-usuario/galaxyflix.git
cd galaxyflix
```

2. Instale as dependências do projeto:

```
composer install
```

3. Crie o arquivo de configuração do ambiente:

```
cp .env.example .env
```

4. Configure o banco de dados no arquivo `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=galaxyflix
DB_USERNAME=root
DB_PASSWORD=
```

5. Gere a chave da aplicação Laravel:

```
php artisan key:generate
```

6. Configure a chave da API TMDB no arquivo `.env`:

```
TMDB_API_KEY=sua_chave_aqui
```

7. Execute as migrations para criar as tabelas no banco de dados:

```
php artisan migrate
```

8. Inicie o servidor local:

```
php artisan serve
```

9. Acesse a aplicação no navegador:

```
http://127.0.0.1:8000/filmes
```

## Uso

Para utilizar o sistema:

1. Acesse a página inicial da aplicação.
2. Clique em "Cadastrar filme".
3. Preencha os campos obrigatórios:

   * Título
   * Gênero
   * Ano
4. Ao salvar, o sistema consulta a API TMDB e busca automaticamente informações adicionais do filme.
5. Os filmes cadastrados aparecem na listagem principal, onde podem ser pesquisados, editados ou excluídos.

## Estrutura do Projeto

```
galaxyflix
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── FilmeController.php
│   └── Models
│       └── Filme.php
├── database
│   └── migrations
│       ├── create_filmes_table.php
│       └── add_campos_api_to_filmes_table.php
├── resources
│   └── views
│       ├── layouts
│       │   └── app.blade.php
│       └── filmes
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
├── routes
│   └── web.php
├── .env
├── artisan
└── README.md
```

## Possíveis Problemas

Erro relacionado a certificado SSL:

Caso apareça erro relacionado ao cURL ou certificado SSL, verifique se o arquivo `cacert.pem` está presente na raiz do projeto e corretamente configurado no ambiente PHP.

Erro de tabela inexistente:

Se o sistema indicar que a tabela não existe, execute novamente:

```
php artisan migrate
```

Erro na consulta da API:

Verifique se a variável `TMDB_API_KEY` está corretamente configurada no arquivo `.env` e se a chave da API está ativa.

## Licença

Este projeto está licenciado sob a licença MIT. Consulte o arquivo LICENSE para mais detalhes.

## Autor

Projeto desenvolvido para fins educacionais utilizando o framework Laravel.
