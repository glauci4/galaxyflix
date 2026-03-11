# GalaxyFlix

GalaxyFlix é uma aplicação web desenvolvida em Laravel para gerenciamento de um catálogo de filmes.

## Descrição

O GalaxyFlix é uma aplicação web desenvolvida para gerenciamento de um catálogo de filmes. O sistema permite cadastrar, listar, editar e excluir registros armazenados em um banco de dados. Durante o cadastro de um filme, ao informar título, gênero e ano, o sistema realiza automaticamente uma requisição à API do TMDB para buscar informações complementares, como imagem de poster, sinopse e avaliação. Esses dados são então armazenados no banco de dados e exibidos na interface da aplicação.

## Objetivo do Projeto

Este projeto tem como objetivo demonstrar, na prática, a utilização do framework Laravel no desenvolvimento de uma aplicação web simples.

A aplicação busca aplicar conceitos estudados na disciplina de Frameworks de Desenvolvimento, como:

* Utilização de frameworks para organização do código
* Adoção do padrão de arquitetura MVC
* Integração com banco de dados

Como recurso adicional, foi realizada também a integração com uma API externa para obtenção automática de informações complementares sobre os filmes.

## Funcionalidades

* Cadastro de filmes
* Listagem de filmes com paginação de 8 itens por página
* Busca de filmes por título, gênero ou ano
* Edição de filmes cadastrados
* Exclusão de filmes
* Exibição de poster do filme
* Visualização de sinopse com opção "Ler mais / Ler menos"
* Integração automática com a API TMDB para obtenção de informações adicionais
* Paginação em português com botões "Anterior" e "Próxima"

## Tecnologias Utilizadas

* Laravel 12 (framework PHP)
* PHP 8 ou superior
* MySQL
* Blade (sistema de templates do Laravel)
* API The Movie Database (TMDB)
* CSS via CDN para estilização da interface

## Requisitos

Antes de executar o projeto, é necessário possuir os seguintes softwares instalados:

* PHP 8.1 ou superior
* Composer
* MySQL
* Git
* Navegador web

## Instalação

Clone o repositório:

```
git clone https://github.com/seu-usuario/galaxyflix.git
```

Entre na pasta do projeto:

```
cd galaxyflix
```

Instale as dependências:

```
composer install
```

Crie o arquivo de configuração do ambiente:

```
cp .env.example .env
```

Configure o banco de dados no arquivo `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=galaxyflix
DB_USERNAME=root
DB_PASSWORD=
```

Gere a chave da aplicação Laravel:

```
php artisan key:generate
```

Configure a chave da API TMDB no arquivo `.env`:

```
TMDB_API_KEY=sua_chave_aqui
```

Execute as migrations para criar as tabelas no banco de dados:

```
php artisan migrate
```

Inicie o servidor local:

```
php artisan serve
```

Acesse a aplicação no navegador:

```
http://127.0.0.1:8000/filmes
```

## Uso

Para utilizar o sistema:

1. Acesse a página principal da aplicação.
2. Clique na opção para cadastrar um novo filme.
3. Preencha os campos obrigatórios:

   * Título
   * Gênero
   * Ano
4. Ao salvar o registro, o sistema consulta automaticamente a API TMDB e obtém informações adicionais do filme.
5. Os filmes cadastrados são exibidos na listagem principal com paginação de 8 itens por página.
6. A partir da listagem é possível pesquisar, editar ou excluir filmes.
7. Para visualizar a sinopse completa, utilize a opção "Ler mais" exibida no card do filme.

## Estrutura do Projeto

```
galaxyflix/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── FilmeController.php
│   └── Models/
│       ├── Filme.php
│       └── User.php
│
├── bootstrap/
├── config/
│
├── database/
│   └── migrations/
│       ├── 2026_03_01_232107_create_filmes_table.php
│       └── 2026_03_08_001553_add_campos_api_to_filmes_table.php
│
├── public/
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── filmes/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── vendor/
│           └── pagination/
│               └── galaxy.blade.php
│
├── routes/
│   └── web.php
│
├── .env
├── artisan
├── cacert.pem
└── README.md
```

## Possíveis Problemas

### Erro relacionado a certificado SSL

Caso ocorra erro relacionado ao cURL ou certificado SSL ao consumir a API, verifique se o arquivo `cacert.pem` está presente na raiz do projeto e corretamente configurado no ambiente PHP.

### Erro de tabela inexistente

Caso o sistema indique que a tabela não existe no banco de dados, execute novamente o comando:

```
php artisan migrate
```

### Erro na consulta da API

Verifique se a variável `TMDB_API_KEY` está corretamente configurada no arquivo `.env` e se a chave da API está ativa.

## Licença

Este projeto está licenciado sob a licença MIT. Consulte o arquivo LICENSE para mais detalhes.

## Autoras

Este projeto foi desenvolvido para fins acadêmicos como parte de uma atividade prática da disciplina, com o objetivo de aplicar conhecimentos de desenvolvimento web utilizando o framework Laravel.

* Geovana — https://github.com/Geovana-02
* Glaucia — https://github.com/glauci4
* Kelly — https://github.com/rye-ishii
* Ketlyn — https://github.com/K3tlyn
