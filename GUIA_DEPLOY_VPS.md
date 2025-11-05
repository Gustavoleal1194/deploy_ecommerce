# 🚀 Guia Completo de Deploy em VPS LocawWeb

Este guia vai te ensinar passo a passo como fazer deploy do projeto em um VPS da LocawWeb.

---

## 📋 Índice

1. [Pré-requisitos](#pré-requisitos)
2. [Preparação do Projeto](#preparação-do-projeto)
3. [Configuração do Servidor VPS](#configuração-do-servidor-vps)
4. [Processo de Deploy](#processo-de-deploy)
5. [Configuração Pós-Deploy](#configuração-pós-deploy)
6. [Troubleshooting](#troubleshooting)

---

## ✅ Pré-requisitos

Antes de começar, você precisa ter:

- ✅ Acesso ao VPS (SSH)
- ✅ Credenciais do banco de dados MySQL
- ✅ Domínio configurado (opcional, mas recomendado)
- ✅ Git instalado no VPS
- ✅ PHP 8.2+ instalado no servidor
- ✅ MySQL/MariaDB instalado
- ✅ Composer instalado no servidor

---

## 🔧 Preparação do Projeto

### 1. Preparar Arquivos para Produção

Antes de fazer deploy, precisamos preparar o projeto:

#### a) Criar arquivo `.env` para produção

```bash
# No seu projeto local, crie um .env.production
cp .env.example .env.production
```

Edite o `.env.production` com as configurações do servidor:
```env
DB_HOST=localhost
DB_NAME=aula_php_mvc
DB_USER=seu_usuario_db
DB_PASS=sua_senha_db
DB_CHARSET=utf8mb4

APP_ENV=production
APP_DEBUG=false

APP_URL=https://seudominio.com.br

LOG_LEVEL=info
LOG_FILE=logs/app.log
```

#### b) Verificar configurações sensíveis

- ✅ Remover senhas hardcoded
- ✅ Usar variáveis de ambiente
- ✅ Verificar `.gitignore` está correto

#### c) Otimizar para produção

```bash
# Limpar cache e arquivos desnecessários
composer install --no-dev --optimize-autoloader
```

---

## 🖥️ Configuração do Servidor VPS

### 1. Conectar ao Servidor via SSH

```bash
# No terminal (ou use PuTTY no Windows)
ssh usuario@ip_do_servidor
# Exemplo: ssh root@192.168.1.100
```

### 2. Instalar Dependências Necessárias

#### PHP e Extensões

```bash
# Atualizar sistema (Ubuntu/Debian)
sudo apt update
sudo apt upgrade -y

# Instalar PHP 8.2 e extensões necessárias
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl -y

# Verificar instalação
php -v
```

#### MySQL/MariaDB

```bash
# Instalar MySQL
sudo apt install mysql-server -y

# Configurar MySQL (definir senha root)
sudo mysql_secure_installation

# Criar banco de dados
sudo mysql -u root -p
```

No MySQL:
```sql
CREATE DATABASE aula_php_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'senha_forte_aqui';
GRANT ALL PRIVILEGES ON aula_php_mvc.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Composer

```bash
# Baixar e instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verificar
composer --version
```

#### Nginx (ou Apache)

**Opção 1: Nginx (Recomendado)**

```bash
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx
```

**Opção 2: Apache**

```bash
sudo apt install apache2 -y
sudo a2enmod rewrite
sudo systemctl start apache2
sudo systemctl enable apache2
```

---

## 📦 Processo de Deploy

### Método 1: Deploy via Git (Recomendado)

Este é o método mais profissional e permite atualizações fáceis.

#### 1. Preparar Repositório Git

No seu computador local:

```bash
# Inicializar Git (se ainda não tiver)
git init
git add .
git commit -m "Initial commit - projeto pronto para deploy"

# Adicionar repositório remoto (GitHub, GitLab, etc.)
git remote add origin https://github.com/seu-usuario/seu-repo.git
git push -u origin main
```

#### 2. No Servidor VPS

```bash
# Criar diretório do projeto
sudo mkdir -p /var/www/aula7
sudo chown -R $USER:$USER /var/www/aula7

# Clonar repositório
cd /var/www/aula7
git clone https://github.com/seu-usuario/seu-repo.git .

# Ou se já tiver o repositório local, fazer push e depois pull no servidor
```

#### 3. Instalar Dependências no Servidor

```bash
cd /var/www/aula7
composer install --no-dev --optimize-autoloader
```

#### 4. Configurar Arquivos

```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Editar .env com as configurações do servidor
nano .env
```

#### 5. Configurar Permissões

```bash
# Criar diretório de logs
mkdir -p logs
chmod 755 logs

# Definir permissões corretas
sudo chown -R www-data:www-data /var/www/aula7
sudo chmod -R 755 /var/www/aula7
sudo chmod -R 775 logs
```

#### 6. Importar Banco de Dados

```bash
# No servidor, importar schema
mysql -u app_user -p aula_php_mvc < database/schema.sql
```

---

### Método 2: Deploy via FTP/SFTP

Para quem não tem Git configurado:

#### 1. Preparar Arquivos Localmente

```bash
# Criar arquivo ZIP (excluindo arquivos desnecessários)
# No Windows, use 7-Zip ou WinRAR
# Inclua apenas:
# - app/
# - config/
# - database/
# - index.php
# - composer.json
# - .env (criar com dados do servidor)
# - vendor/ (ou instalar no servidor)
```

#### 2. Upload via FTP

Use FileZilla ou similar:
- Host: IP do servidor
- Usuário: seu usuário FTP
- Senha: sua senha FTP
- Porta: 21 (FTP) ou 22 (SFTP)

Upload para: `/var/www/html/aula7` ou `/public_html/aula7`

#### 3. No Servidor

```bash
# Descompactar (se necessário)
cd /var/www/html/aula7
unzip projeto.zip

# Instalar dependências
composer install --no-dev --optimize-autoloader

# Configurar permissões
chmod -R 755 .
chmod -R 775 logs
```

---

## ⚙️ Configuração do Servidor Web

### Nginx

Criar arquivo de configuração:

```bash
sudo nano /etc/nginx/sites-available/aula7
```

Conteúdo:

```nginx
server {
    listen 80;
    server_name seudominio.com.br www.seudominio.com.br;
    root /var/www/aula7;
    index index.php;

    # Logs
    access_log /var/log/nginx/aula7_access.log;
    error_log /var/log/nginx/aula7_error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Negar acesso a arquivos sensíveis
    location ~ /\. {
        deny all;
    }

    location ~ /(vendor|config|database|tests|\.git) {
        deny all;
    }
}
```

Ativar site:

```bash
sudo ln -s /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache

Criar arquivo `.htaccess` no diretório raiz:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /aula7/
    
    # Redirecionar tudo para index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Proteger arquivos sensíveis
<FilesMatch "\.(env|git|md)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

Configurar Virtual Host:

```bash
sudo nano /etc/apache2/sites-available/aula7.conf
```

```apache
<VirtualHost *:80>
    ServerName seudominio.com.br
    ServerAlias www.seudominio.com.br
    DocumentRoot /var/www/aula7

    <Directory /var/www/aula7>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/aula7_error.log
    CustomLog ${APACHE_LOG_DIR}/aula7_access.log combined
</VirtualHost>
```

Ativar:

```bash
sudo a2ensite aula7.conf
sudo systemctl reload apache2
```

---

## 🔒 Configuração Pós-Deploy

### 1. SSL/HTTPS (Recomendado)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx -y

# Para Nginx
sudo certbot --nginx -d seudominio.com.br

# Para Apache
sudo certbot --apache -d seudominio.com.br
```

### 2. Firewall

```bash
# Permitir portas necessárias
sudo ufw allow 22/tcp   # SSH
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS
sudo ufw enable
```

### 3. Configurar Cron Jobs (se necessário)

```bash
crontab -e
```

### 4. Backup Automatizado

Criar script de backup:

```bash
sudo nano /usr/local/bin/backup-aula7.sh
```

---

## ✅ Checklist de Deploy

Use este checklist antes de fazer deploy:

### Pré-Deploy

- [ ] Testes passando localmente (`composer test`)
- [ ] `.env` configurado para produção
- [ ] `APP_DEBUG=false` em produção
- [ ] Senhas e secrets em variáveis de ambiente
- [ ] `.gitignore` configurado corretamente
- [ ] Banco de dados de produção criado
- [ ] Usuário do banco com permissões corretas

### No Servidor

- [ ] PHP 8.2+ instalado
- [ ] Extensões PHP necessárias instaladas
- [ ] MySQL/MariaDB instalado e configurado
- [ ] Composer instalado
- [ ] Nginx/Apache configurado
- [ ] Permissões de arquivos corretas
- [ ] Diretório de logs criado e com permissões
- [ ] Banco de dados importado
- [ ] `.env` configurado no servidor
- [ ] SSL/HTTPS configurado (recomendado)

### Pós-Deploy

- [ ] Aplicação acessível via navegador
- [ ] Login funcionando
- [ ] CRUD de produtos funcionando
- [ ] CRUD de categorias funcionando
- [ ] Logs sendo gerados
- [ ] Sem erros no log do servidor
- [ ] Performance adequada

---

## 🐛 Troubleshooting

### Erro 500 - Internal Server Error

```bash
# Ver logs do PHP
sudo tail -f /var/log/php8.2-fpm.log

# Ver logs do Nginx/Apache
sudo tail -f /var/log/nginx/error.log
# ou
sudo tail -f /var/log/apache2/error.log

# Verificar permissões
ls -la /var/www/aula7
```

### Erro de Conexão com Banco

```bash
# Testar conexão
mysql -u app_user -p aula_php_mvc

# Verificar se MySQL está rodando
sudo systemctl status mysql

# Verificar configuração no .env
cat .env | grep DB_
```

### Erro de Permissão

```bash
# Corrigir permissões
sudo chown -R www-data:www-data /var/www/aula7
sudo chmod -R 755 /var/www/aula7
sudo chmod -R 775 logs
```

### Composer não encontrado

```bash
# Verificar se está no PATH
which composer

# Se não estiver, adicionar ao PATH
export PATH="$PATH:/usr/local/bin"
```

### Página em Branco

1. Verificar `display_errors` no PHP
2. Verificar logs do servidor
3. Verificar se `index.php` está no lugar correto
4. Verificar se o roteamento está funcionando

---

## 📝 Scripts de Deploy Automatizado

Use os scripts fornecidos em `scripts/deploy.sh` adaptado para seu servidor.

---

## 🔄 Atualizações Futuras

Para atualizar o projeto após deploy inicial:

```bash
# Via Git (método recomendado)
cd /var/www/aula7
git pull origin main
composer install --no-dev --optimize-autoloader
sudo systemctl reload nginx  # ou apache2
```

---

## 📞 Suporte

Se encontrar problemas:
1. Verificar logs do servidor
2. Verificar logs da aplicação (`logs/app.log`)
3. Verificar configurações do `.env`
4. Consultar documentação do LocawWeb

---

**Última atualização**: 2025-11-05

