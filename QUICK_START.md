# 🚀 Guia de Início Rápido - DevOps

## ⚡ Setup em 3 Passos

### 1. Instalar Dependências

```bash
composer install
```

### 2. Iniciar Ambiente Docker

```bash
docker-compose up -d
```

### 3. Acessar Aplicação

- **Aplicação**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Login**: admin@teste.com / 123456

---

## 🧪 Executar Testes

```bash
# Testes básicos
composer test

# Com cobertura
composer test-coverage
```

---

## 📊 Pipeline CI Local

```bash
# Executar tudo localmente
composer ci

# Ou passo a passo
composer test
composer phpstan
composer cs-check
```

---

## 🐳 Comandos Docker Úteis

```bash
# Iniciar
docker-compose up -d

# Ver logs
docker-compose logs -f

# Parar
docker-compose down

# Rebuild
docker-compose up -d --build
```

---

## 📚 Documentação Completa

- **DEVOPS.md** - Guia completo de DevOps
- **RESUMO_DEVOPS.md** - Resumo executivo
- **README.md** - Documentação geral do projeto

---

## ⚠️ Problemas Comuns

**Porta já em uso?**
```bash
# Parar outros serviços nas portas 8080, 3306, 8081
docker-compose down
```

**Testes falhando?**
```bash
# Criar banco de teste
mysql -u root -e "CREATE DATABASE aula_php_mvc_test;"
```

**Permissões?**
```bash
# Criar diretório de logs
mkdir -p logs
chmod 755 logs
```

---

**Pronto!** 🎉 Seu ambiente DevOps está configurado.

