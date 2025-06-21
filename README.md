# Smartlead Addresses

Sistema de Endereços: Gerencie usuários e seus endereços com integração automática do ViaCEP.

## Tecnologias Utilizadas

- **Laravel 11** (API RESTful)
- **Vue.js 3** (Frontend SPA)
- **Docker** e **Docker Compose**
- **MySQL**
- **Axios** (requisições HTTP)
- **CSS puro** com `<style scoped>`
- **Testes automatizados** (PHPUnit)

---

##  Pré-requisitos

Antes de começar, você precisa ter instalado **TODOS** os itens abaixo:

- [Docker](https://www.docker.com/products/docker-desktop) (obrigatório)
- [Docker Compose](https://docs.docker.com/compose/) (já vem junto no Docker Desktop)
- [Git](https://git-scm.com/) (para clonar o repositório)
- [Node.js 18+](https://nodejs.org/) (recomendado para desenvolvimento local, mas não obrigatório se usar só Docker)
- [Yarn](https://classic.yarnpkg.com/lang/en/docs/install/) (gerenciador de pacotes Node, use `npm install -g yarn`)

> **Atenção:** Não é necessário instalar PHP, Composer ou MySQL na sua máquina. Tudo roda dentro dos containers Docker.

---

## Passo a Passo para Rodar o Projeto

### 1. Clone o repositório

```sh
git clone https://github.com/seu-usuario/smartlead-addresses.git
cd smartlead-addresses
```

### 2. Suba os containers Docker

```sh
docker-compose down
docker-compose up -d
```

Isso irá criar três containers:
- `app` (Laravel + Node)
- `db` (MySQL)
- `web` (Nginx)

### 3. Instale as dependências do frontend

```sh
docker-compose exec app yarn install
```

### 4. Rode as migrations do banco de dados

```sh
docker-compose exec app php artisan migrate
```

### 5. Rode os seeders para popular o banco de dados

```sh
docker-compose exec app php artisan db:seed
```

### 6. Inicie o servidor de desenvolvimento do Vite (frontend)

```sh
docker-compose exec app yarn dev
```

> O Vite já está configurado para rodar na porta 5173 e aceitar conexões externas.

### 7. Acesse a aplicação

Abra o navegador em: [http://localhost:8000](http://localhost:8000)

---

## Rodando os Testes

Para rodar os testes automatizados do backend:

```sh
docker-compose exec app php artisan test
```

---

## Estrutura do Projeto

- `app/` - Backend Laravel (controllers, models, requests)
- `resources/js/components/` - Componentes Vue.js 3 (SPA)
- `resources/views/welcome.blade.php` - View principal que carrega o Vue
- `docker/` - Configurações de Docker e Nginx
- `database/` - Migrations
- `tests/` - Testes automatizados (PHPUnit)

---

## Dicas e Solução de Problemas

- **Erro de dependências Node:**
  - Rode `docker-compose exec app yarn install` novamente.
  - Se persistir, apague `node_modules` e `yarn.lock` e rode `yarn install` de novo.
- **Porta 5173 não acessível:**
  - Certifique-se que o Vite está rodando (`docker-compose exec app yarn dev`).
  - A porta 5173 está exposta no `docker-compose.yml`.
- **Banco de dados não conecta:**
  - Aguarde alguns segundos após subir os containers antes de rodar as migrations.
- **Mudanças no código não aparecem:**
  - Certifique-se de que o Vite está rodando e recarregue a página.

---

## Funcionalidades

- Cadastro de usuários com endereço preenchido automaticamente via ViaCEP (CEP)
- Listagem paginada de usuários em formato de cartões
- Interface moderna, responsiva e intuitiva
- API versionada (`/api/v1`)
- Máscara de CEP e loading no frontend
- Testes de unidade e integração

---

##  Contato

Dúvidas? Fale com o desenvolvedor!

---

## Deploy

### Vercel (Frontend)

> Instruções para deploy na Vercel serão adicionadas aqui.

> Projeto desenvolvido para desafio técnico. Qualquer dúvida, sugestão ou bug, abra uma issue ou entre em contato.
