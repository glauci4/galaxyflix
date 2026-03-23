# GalaxyFlix

> Aplicação web em Laravel para gerenciamento de um catálogo de filmes.

---

## Índice

- [Descrição](#descrição)
- [Funcionalidades](#funcionalidades)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalação rápida](#instalação-rápida)
- [Configuração](#configuração)
- [Uso](#uso)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Possíveis problemas](#possíveis-problemas)
- [Licença](#licença)
- [Autoras](#autoras)

---

## Descrição

GalaxyFlix é uma aplicação web desenvolvida em Laravel para gerenciar um catálogo de filmes. O sistema permite cadastrar, listar, editar e excluir registros armazenados em um banco de dados. Durante o cadastro de um filme, ao informar título, gênero e ano, o sistema realiza automaticamente uma requisição à API do TMDB para buscar informações complementares (poster, sinopse e avaliação). Esses dados são então armazenados no banco e exibidos na interface.

O objetivo do projeto é demonstrar o uso do Laravel, a aplicação do padrão MVC e a integração com APIs externas em um contexto acadêmico.

## Funcionalidades

- Cadastro de filmes
- Listagem de filmes com paginação de 4 registros por página
- Busca por título, gênero ou ano
- Edição e exclusão de filmes
- Exibição de poster do filme
- Visualização de sinopse com opção "Ler mais / Ler menos"
- Integração automática com a API TMDB
- Navegação entre páginas com botões "Anterior" e "Próxima"

## Tecnologias

- Laravel 12
- PHP 8.1+
- MySQL
- Blade (templates)
- API The Movie Database (TMDB)
- Docker

## Requisitos


- PHP 8.1 ou superior (extensões: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML) - para instalação manual
- Composer - para instalação manual
- MySQL 5.7 ou superior - para instalação manual
- Docker Desktop - para execução via container
- Git
- Navegador web

### Verificação rápida

```bash
php -v
composer -v
mysql --version
git --version
docker --version
```

## Instalação rápida

### Opção 1: com Docker (recomendado)

```bash
# Clone o repositório
git clone https://github.com/glauci4/galaxyflix.git
cd galaxyflix

# Build da imagem
docker build -t galaxyflix .

# Execute o container
docker run -d -p 8080:80 --name galaxyflix-app galaxyflix

# Acesse no navegador
http://localhost:8080/filmes
```

### Opção 2: Manual (sem Docker)

```bash
git clone https://github.com/glauci4/galaxyflix.git
cd galaxyflix
composer install
cp .env.example .env
```

Acesse o MySQL e crie o banco de dados:

```sql
mysql -u root -p
CREATE DATABASE galaxyflix;
EXIT;
```

Gere a chave da aplicação e rode as migrations:

```bash
php artisan key:generate
php artisan migrate
```

Inicie o servidor local:

```bash
php artisan serve
# Acesse: http://127.0.0.1:8000/filmes
```

## Configuração

### Para execução com Docker

O Dockerfile já utiliza o arquivo `.env` existente no projeto. Certifique-se de que ele contém:

```env
DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=galaxyflix
DB_USERNAME=root
DB_PASSWORD=sua_senha
TMDB_API_KEY=sua_chave_aqui
```
- Se estiver usando Docker no Windows, o banco de dados pode estar no host. Utilize `DB_HOST=host.docker.internal` para conectar ao MySQL do Windows.
- Alternativamente, você pode passar as variáveis diretamente no comando `docker run`:

```bash
docker run -d -p 8080:80 \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_DATABASE=galaxyflix \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=sua_senha \
  -e TMDB_API_KEY=sua_chave_api \
  -e APP_KEY=gerado_pelo_laravel \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  --name galaxyflix-app galaxyflix
```

### Para execução manual (sem Docker)

- Configure o arquivo `.env` com as credenciais do banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=galaxyflix
DB_USERNAME=root
DB_PASSWORD=
```

- Defina a chave da API TMDB no `.env`:

```env
TMDB_API_KEY=sua_chave_aqui
```

- Se ocorrer erro de cURL/SSL ao consumir a API (cURL error 60), baixe `cacert.pem` em `https://curl.se/ca/cacert.pem` e coloque na raiz do projeto (mesma pasta do `artisan`).

## Uso

1. Acesse a página principal.
2. Cadastre um novo filme (Título, Gênero, Ano).
3. Ao salvar, a aplicação consulta o TMDB e salva os dados complementares.
4. Use a listagem para buscar, editar ou excluir filmes.
5. Para ler a sinopse completa, clique em "Ler mais" no card do filme.

## Estrutura do projeto

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
├── Dockerfile
└── README.md
```

## Possíveis problemas

### Geral
- Erro cURL/SSL (cURL error 60): verifique se `cacert.pem` está presente na raiz do projeto.
- Tabela inexistente: execute `php artisan migrate`.
- Erro na consulta da API: confirme `TMDB_API_KEY` no `.env`.
- Permissão (Linux/macOS): execute `chmod -R 775 storage bootstrap/cache`.
- Porta ocupada: use `php artisan serve --port=8001`.

### Docker
- **Container não sobe**: verifique os logs com `docker logs galaxyflix-app`.
- **Erro de conexão com banco**: confirme se `DB_HOST=host.docker.internal` está configurado no `.env` ou nas variáveis.
- **Porta 8080 já em uso**: altere a porta no comando `docker run -p 8081:80`.
- **Permissão negada no Windows**: execute o PowerShell como administrador.
- **Docker Desktop não está rodando**: inicie o Docker Desktop antes de executar os comandos.

## Licença

Projeto licenciado sob MIT — consulte o arquivo `LICENSE`.

## Autoras

Este projeto foi desenvolvido como parte da disciplina de Frameworks de Desenvolvimento, com o objetivo de aplicar na prática os conceitos estudados sobre o framework Laravel, arquitetura MVC, integração com banco de dados e consumo de APIs externas. A aplicação foi construída de forma colaborativa, permitindo que cada integrante da equipe contribuísse com o desenvolvimento, documentação e testes do sistema.

- Geovana (https://github.com/Geovana-02)
- Glaucia (https://github.com/glauci4)
- Kelly (https://github.com/rye-ishii)
- Ketlyn (https://github.com/K3tlyn)




