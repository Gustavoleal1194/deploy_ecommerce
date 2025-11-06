# 🚀 Configuração do Ambiente na VPS

## 📋 Resumo

Este documento descreve como foi configurado o ambiente de produção na VPS Locaweb para o Sistema de Gerenciamento de Produtos.

## 🖥️ Infraestrutura Implementada

### Stack Tecnológica
- **Sistema Operacional**: Debian 12 (Bookworm)
- **Servidor Web**: Nginx 1.22.1
- **Linguagem**: PHP 8.2.29 (PHP-FPM)
- **Banco de Dados**: MySQL/MariaDB 8.0
- **Gerenciador de Pacotes**: Composer 2.8.12
- **Controle de Versão**: Git

### Servidor
- **IP**: 191.252.93.136
- **Provedor**: Locaweb VPS
- **Diretório de Deploy**: `/var/www/aula7`

---

## 🔧 Configuração do Ambiente

### 1. Instalação Base (Cloud-Init)

A configuração inicial foi automatizada usando cloud-init durante a criação da VPS:

```yaml
# Configuração automática via painel Locaweb
- Instalação de pacotes essenciais
- Configuração de firewall (UFW)
- Instalação de PHP 8.2, Nginx, MySQL
- Instalação do Composer
- Criação de usuários e permissões
- Configuração de segurança (Fail2Ban)
```

### 2. Configuração do PHP

**PHP-FPM** configurado para processar requisições PHP:

```bash
# Pacotes instalados
php8.2-fpm
php8.2-mysql
php8.2-mbstring
php8.2-xml
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-cli

# Configurações PHP (/etc/php/8.2/fpm/php.ini)
upload_max_filesize = 20M
post_max_size = 20M
cgi.fix_pathinfo = 0
```

**Socket PHP-FPM**: `/var/run/php/php8.2-fpm.sock`

### 3. Configuração do Nginx

**Arquivo de configuração**: `/etc/nginx/sites-available/aula7`

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/aula7;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }
}
```

**Ativação**:
```bash
ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7
rm -f /etc/nginx/sites-enabled/default
systemctl reload nginx
```

### 4. Configuração do Banco de Dados

**Banco de dados**: `aula_php_mvc`
**Usuário da aplicação**: `app_user`

```sql
CREATE DATABASE aula_php_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'senha_segura';
GRANT ALL PRIVILEGES ON aula_php_mvc.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

**Arquivo de configuração**: `.env`
```env
DB_HOST=localhost
DB_NAME=aula_php_mvc
DB_USER=app_user
DB_PASS=senha_segura
DB_CHARSET=utf8mb4
APP_ENV=production
APP_DEBUG=false
```

### 5. Estrutura de Diretórios

```
/var/www/aula7/
├── app/              # Código da aplicação
├── config/           # Arquivos de configuração
├── database/         # Scripts SQL
├── tests/            # Testes automatizados
├── logs/             # Logs da aplicação
├── vendor/           # Dependências Composer
├── .env              # Variáveis de ambiente
└── index.php         # Ponto de entrada
```

**Permissões**:
```bash
chown -R www-data:www-data /var/www/aula7
chmod -R 755 /var/www/aula7
chmod -R 775 logs
```

### 6. Deploy via Git

```bash
cd /var/www/aula7
git config --global --add safe.directory /var/www/aula7
git pull origin main
composer install --no-dev --optimize-autoloader
```

### 7. Segurança

- **Firewall UFW**: Portas 22 (SSH), 80 (HTTP), 443 (HTTPS) abertas
- **Fail2Ban**: Proteção contra brute force
- **Nginx**: Bloqueio de acesso a arquivos sensíveis (`.env`, `vendor/`, `config/`)
- **PHP**: Configurações de segurança ativadas
- **MySQL**: Usuário específico para aplicação (não root)

---

## 🐳 Alternativa: Deploy com Docker (Opcional)

Embora não tenha sido usado na VPS atual, o projeto possui configuração Docker para desenvolvimento e pode ser adaptado para produção.

### Arquivos Docker

- **Dockerfile**: Imagem PHP 8.2-FPM
- **docker-compose.yml**: Orquestração completa (app, nginx, db, phpmyadmin)

### Para usar Docker na VPS:

1. Instalar Docker e Docker Compose
2. Clonar repositório
3. Configurar `.env` para Docker
4. Executar `docker-compose up -d`

**Vantagens do Docker**:
- Isolamento de ambientes
- Facilita atualizações
- Consistência entre dev/staging/prod
- Rollback mais simples

---

## 📊 Monitoramento e Logs

### Logs da Aplicação
- **Localização**: `/var/www/aula7/logs/app.log`
- **Classe**: `App\Logger`

### Logs do Nginx
- **Access**: `/var/log/nginx/aula7_access.log`
- **Error**: `/var/log/nginx/aula7_error.log`

### Logs do PHP-FPM
- **Localização**: `/var/log/php8.2-fpm.log`

### Comandos de Monitoramento

```bash
# Ver logs em tempo real
tail -f /var/www/aula7/logs/app.log
tail -f /var/log/nginx/aula7_error.log

# Status dos serviços
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql
```

---

## ✅ Checklist de Configuração

- [x] PHP 8.2 instalado e configurado
- [x] Nginx configurado e servindo aplicação
- [x] MySQL configurado com banco e usuário
- [x] Composer instalado e dependências baixadas
- [x] Arquivo `.env` configurado
- [x] Permissões de arquivos ajustadas
- [x] Firewall configurado
- [x] Git configurado para atualizações
- [x] Logs configurados
- [x] Testes automatizados funcionando

---

## 🔄 Processo de Deploy

1. **Desenvolvimento Local**: Fazer alterações e commits
2. **Push para GitHub**: `git push origin main`
3. **No Servidor**: `git pull` em `/var/www/aula7`
4. **Instalar Dependências**: `composer install --no-dev`
5. **Regenerar Autoloader**: `composer dump-autoload`
6. **Recarregar Nginx**: `systemctl reload nginx`

---

## 🧪 Testes Automatizados

### Executar Testes

```bash
# Todos os testes
./vendor/bin/phpunit tests/

# Apenas testes unitários
./vendor/bin/phpunit tests/Unit/

# Apenas testes de integração
./vendor/bin/phpunit tests/Integration/

# Testes de endpoints com relatório
./vendor/bin/phpunit tests/Integration/EndpointTest.php
cat relatorio-endpoints.txt
```

### Cobertura de Testes

- **Modelos**: Categoria, Produto, Usuario
- **Endpoints API**: 10 endpoints testados
- **Validações**: Nome, email, senha, preço

---

## 📝 Notas Importantes

1. **Não usar Docker na VPS atual**: Configuração é direta (PHP + Nginx + MySQL)
2. **Docker disponível**: Para desenvolvimento local e futuras implementações
3. **Segurança**: Usuário `app_user` para banco, não usar root
4. **Case-sensitive**: Linux diferencia maiúsculas/minúsculas em nomes de arquivos
5. **Permissões**: `www-data` é o usuário do Nginx/PHP-FPM

---

## 🔗 Links Úteis

- **URL do Sistema**: http://191.252.93.136
- **Repositório**: GitHub
- **Documentação DevOps**: Ver `DEVOPS.md`

---

**Data de Configuração**: 05/11/2025  
**Última Atualização**: 06/11/2025

