# Sistema de Gerenciamento de Produtos

Sistema web para gerenciamento de produtos e categorias desenvolvido em PHP 8+ com arquitetura MVC, utilizando MySQL e PDO para persistência de dados.

## Requisitos

- PHP 8.2 ou superior
- MySQL 5.7 ou superior (ou MariaDB 10.2+)
- Composer (para autoload PSR-4)
- Extensões PHP: PDO, PDO_MySQL, mbstring

## Estrutura do Projeto

```
aula7/
├── app/
│   ├── Controllers/          # Controladores MVC
│   │   ├── AuthController.php      # Autenticação
│   │   ├── CategoriaController.php
│   │   └── ProdutoController.php
│   ├── Models/               # Models (acesso a dados)
│   │   ├── Model.php         # Classe base
│   │   ├── Categoria.php
│   │   ├── Produto.php
│   │   └── Usuario.php
│   ├── Views/                # Views/Templates
│   │   ├── auth/             # Views de autenticação
│   │   ├── Categorias/
│   │   ├── Produtos/
│   │   └── header.php        # Header comum
│   └── Database.php          # Classe de conexão PDO
├── config/
│   └── db.php                # Configurações do banco
├── database/
│   └── schema.sql            # Script de criação das tabelas
├── index.php                 # Roteador principal
└── composer.json             # Dependências PHP
```

## Setup do Banco de Dados

### 1. Criar o Banco de Dados

Execute o script SQL para criar o banco e as tabelas:

```bash
# Via linha de comando (XAMPP)
C:\xampp\mysql\bin\mysql.exe -u root -p < database/schema.sql

# Ou via phpMyAdmin:
# 1. Acesse http://localhost/phpmyadmin
# 2. Clique na aba "SQL"
# 3. Copie e cole o conteúdo de database/schema.sql
# 4. Clique em "Executar"
```

O script cria:
- Banco de dados: `aula_php_mvc`
- Tabelas: `categorias`, `produtos`, `usuarios`
- Foreign Keys e dados iniciais

### 2. Configurar Conexão

Edite `config/db.php` e ajuste as credenciais:

```php
return [
    'host' => 'localhost',
    'dbname' => 'aula_php_mvc',
    'user' => 'root',
    'pass' => '',  // Sua senha do MySQL
    'charset' => 'utf8mb4',
    // ...
];
```

### 3. Instalar Dependências

```bash
composer install
```

## Como Rodar o Projeto

### Opção 1: Servidor Built-in do PHP

```bash
cd aula7
php -S localhost:8080 -t .
```

Acesse: `http://localhost:8080/aula_php/aula7/login`

### Opção 2: XAMPP (Apache)

1. Coloque a pasta `aula7` em `C:\xampp\htdocs\`
2. Inicie Apache e MySQL no XAMPP Control Panel
3. Acesse: `http://localhost/aula_php/aula7/login`

## Usuários de Teste

O sistema vem com 2 usuários pré-cadastrados:

### Administrador
- **Email**: `admin@teste.com`
- **Senha**: `123456`
- **Tipo**: Administrador

### Usuário Comum
- **Email**: `usuario@teste.com`
- **Senha**: `123456`
- **Tipo**: Usuário

## Rotas Principais

### Rotas Web (Requerem Autenticação)

#### Autenticação
- `GET /login` - Página de login
- `POST /api/login` - Processar login
- `GET /logout` - Fazer logout

#### Categorias
- `GET /categorias` - Listar categorias
- `GET /categorias/criar` - Formulário de criação
- `POST /api/categorias` - Criar categoria
- `GET /categorias/ver?id=X` - Visualizar categoria (mostra produtos relacionados)
- `GET /categorias/editar?id=X` - Formulário de edição
- `POST /api/categorias/editar` - Atualizar categoria
- `POST /api/categorias/deletar` - Deletar categoria
- `GET /categorias/buscar?busca=termo` - Buscar categorias

#### Produtos
- `GET /produtos` - Listar produtos (com nome da categoria)
- `GET /produtos/criar` - Formulário de criação
- `POST /api/produtos` - Criar produto
- `GET /produtos/ver?id=X` - Visualizar produto (mostra categoria)
- `GET /produtos/editar?id=X` - Formulário de edição
- `POST /api/produtos/editar` - Atualizar produto
- `POST /api/produtos/deletar` - Deletar produto
- `GET /produtos/buscar?busca=termo` - Buscar produtos

### Rotas API JSON

Todas as APIs retornam `Content-Type: application/json`

#### Categorias
- `GET /api/categorias` - Listar todas as categorias (JSON)
- `GET /api/categorias/ver?id=X` - Detalhes de uma categoria (JSON)
- `GET /api/categorias/buscar?nome=termo` - Buscar categorias (JSON)

#### Produtos
- `GET /api/produtos` - Listar todos os produtos (JSON)
- `GET /api/produtos/ver?id=X` - Detalhes de um produto (JSON)
- `GET /api/produtos/buscar?nome=termo` - Buscar produtos (JSON)

**Exemplo de resposta JSON:**
```json
[
  {
    "id": 1,
    "nome": "Notebook",
    "preco": "3500.00",
    "categoria_id": 1,
    "categoria_nome": "Eletrônicos",
    "created_at": "2025-10-26 12:59:41",
    "updated_at": "2025-10-26 12:59:41"
  }
]
```

## Funcionalidades Implementadas

### ✅ CRUD Completo
- **Categorias**: Criar, Listar, Visualizar, Editar, Excluir
- **Produtos**: Criar, Listar, Visualizar, Editar, Excluir
- **Usuários**: Sistema de autenticação

### ✅ Relacionamentos Visíveis na UI
- Produtos mostram a categoria à qual pertencem
- Categorias mostram lista de produtos relacionados (na página de detalhes)

### ✅ Busca
- Busca em categorias (`/categorias/buscar`)
- Busca em produtos (`/produtos/buscar`)
- Redirecionamento inteligente (se encontrar 1 resultado, vai direto)

### ✅ Autenticação
- Sistema de login/logout
- Rotas protegidas (private) - todas as rotas (exceto login) requerem autenticação
- Sessão PHP para manter estado de autenticação

### ✅ Validações Server-side
- Campos obrigatórios validados
- Formatos numéricos validados (preço > 0)
- Validação de email
- Integridade referencial (verifica se categoria existe ao criar produto)
- Senha mínima 6 caracteres
- Validações centralizadas nas Models

### ✅ Mensagens de Erro/Sucesso
- Mensagens visíveis na interface
- Tratamento de exceções com feedback ao usuário
- Exibição via parâmetros GET na URL

## Banco de Dados

### Entidades

1. **categorias** - Categorias de produtos
2. **produtos** - Produtos cadastrados (relacionado com categorias)
3. **usuarios** - Usuários do sistema (para autenticação)

### Relacionamentos

- `produtos.categoria_id` → `categorias.id` (FOREIGN KEY, CASCADE DELETE)

### Schema

Ver arquivo completo em: `database/schema.sql`

## Segurança

- **Prepared Statements**: Todas as queries usam prepared statements (proteção contra SQL Injection)
- **Password Hashing**: Senhas armazenadas com `password_hash()` (bcrypt)
- **Rotas Protegidas**: Sistema de autenticação impede acesso não autorizado
- **Validação de Dados**: Validações server-side em todas as entradas
- **Sanitização**: Output escapado com `htmlspecialchars()`

## Tecnologias

- **PHP 8.2+** - Linguagem principal
- **MySQL** - Banco de dados relacional
- **PDO** - Camada de abstração de banco de dados
- **Composer** - Gerenciamento de dependências
- **PSR-4** - Autoloading de classes
- **MVC Pattern** - Arquitetura da aplicação

## Características Técnicas

- **Singleton Pattern** para conexão de banco
- **Active Record Pattern** nas Models
- **Separação de Responsabilidades** (MVC)
- **Reutilização de Código** (Models base)
- **Tratamento de Exceções** robusto
- **Código Limpo** e organizado

## Estrutura de Arquivos Importantes

- `index.php` - Roteador principal (gerencia todas as rotas)
- `config/db.php` - Configurações de conexão MySQL
- `app/Database.php` - Classe singleton para conexão PDO
- `app/Models/Model.php` - Classe base para Models
- `database/schema.sql` - Script de criação das tabelas

## 🔄 DevOps & CI/CD

Este projeto implementa práticas modernas de DevOps para garantir qualidade, automação e confiabilidade.

### ✅ Funcionalidades DevOps Implementadas

- **✅ Integração Contínua (CI)**: Pipeline automatizado com GitHub Actions
  - 🧪 Testes unitários e de integração automáticos
  - 🔍 Análise estática de código (PHPStan)
  - ✨ Verificação de padrões (PHPCS)
  - 🔒 Análise de segurança de dependências
  
- **✅ Entrega Contínua (CD)**: Deploy automático para VPS
  - 🚀 Deploy automático via SSH e rsync
  - 📦 Backup automático antes de cada deploy
  - 🔄 Rollback fácil em caso de falha
  - 🌐 Deploy para VPS Locaweb (Ubuntu 20.04)
  
- **✅ Testes Automatizados**: PHPUnit para testes unitários e de integração
- **✅ Containerização**: Docker e Docker Compose para ambiente isolado
- **✅ Monitoramento**: Sistema de logs estruturado (`App\Logger`)
- **✅ Qualidade de Código**: PHPStan e PHP CodeSniffer integrados
- **✅ Versionamento**: Git configurado com `.gitignore` adequado

### 🚀 Pipeline CI/CD

![CI Status](https://github.com/Gustavoleal1194/deploy_ecommerce/workflows/CI%20-%20Testes%20e%20Qualidade/badge.svg)
![CD Status](https://github.com/Gustavoleal1194/deploy_ecommerce/workflows/CD%20-%20Deploy%20para%20VPS%20(Locaweb)/badge.svg)

**Fluxo Automático:**
```
git push → CI (testes) → ✅ → CD (deploy) → 🚀 Site atualizado!
```

### 🚀 Início Rápido com Docker

```bash
# Iniciar ambiente completo
docker-compose up -d

# Acessar aplicação
http://localhost:8080

# Acessar phpMyAdmin
http://localhost:8081
```

### 🧪 Executar Testes

```bash
# Instalar dependências de desenvolvimento
composer install

# Executar todos os testes
composer test

# Testes com cobertura de código
composer test-coverage
```

### 📊 Pipeline CI/CD

O pipeline está configurado em `.github/workflows/ci.yml` e executa automaticamente:
- Testes unitários e de integração
- Análise estática de código (PHPStan)
- Verificação de padrões de código (PHPCS)
- Análise de segurança de dependências
- Build da imagem Docker

### 📚 Documentação Completa

Para informações detalhadas sobre DevOps e CI/CD, consulte:
- **[DEVOPS.md](DEVOPS.md)** - Guia completo de DevOps
- **[.github/workflows/SETUP.md](.github/workflows/SETUP.md)** - 🚀 **Configuração CI/CD para VPS** (passo-a-passo)

### 🎯 Como Configurar CI/CD

**Leia o guia completo:** [.github/workflows/SETUP.md](.github/workflows/SETUP.md)

**Resumo rápido:**
1. Gerar chave SSH
2. Adicionar 4 secrets no GitHub (`SSH_PRIVATE_KEY`, `VPS_HOST`, `VPS_USER`, `VPS_PATH`)
3. Configurar diretório na VPS
4. Fazer push → Deploy automático! ✅

### 🛠️ Scripts Úteis

```bash
# Usando Makefile (Linux/Mac)
make help          # Ver todos os comandos
make setup         # Setup completo
make test          # Executar testes
make docker-up     # Iniciar Docker

# Usando Composer
composer ci        # Pipeline completo local
composer phpstan   # Análise estática
composer cs-check  # Verificar código
```

### 📝 Logs e Monitoramento

Os logs são salvos em `logs/app.log` com formato estruturado:

```php
use App\Logger;

Logger::info('Operação realizada', ['user_id' => 123]);
Logger::error('Erro ocorrido', ['error_code' => 500]);
Logger::metric('response_time', 150.5, 'ms');
```