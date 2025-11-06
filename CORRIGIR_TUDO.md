# 🔧 Correção Completa - 404 e Erro de Banco de Dados

## Problemas Identificados

1. **404 Not Found** - Nginx ainda não está servindo o site corretamente
2. **Erro de Banco**: "Access denied for user 'root'@'localhost'" - Aplicação está tentando usar 'root' em vez de 'app_user'

## Solução Passo a Passo

Execute no servidor:

```bash
cd /var/www/aula7

# 1. DIAGNÓSTICO PRIMEIRO
chmod +x scripts/diagnostico-completo.sh
./scripts/diagnostico-completo.sh
```

### Corrigir Nginx

```bash
# 1. Verificar se o arquivo existe
cat /etc/nginx/sites-available/aula7

# 2. Se não existir ou estiver errado, criar/corrigir
sudo tee /etc/nginx/sites-available/aula7 > /dev/null <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/aula7;
    index index.php;

    access_log /var/log/nginx/aula7_access.log;
    error_log /var/log/nginx/aula7_error.log;
    client_max_body_size 20M;

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

    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location ~ ^/(vendor|config|database|tests|\.git|scripts|\.github) {
        deny all;
        access_log off;
        log_not_found off;
    }
}
EOF

# 3. Habilitar site
sudo ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7

# 4. Remover site padrão
sudo rm -f /etc/nginx/sites-enabled/default

# 5. Verificar sites ativos
ls -la /etc/nginx/sites-enabled/

# 6. Testar e recarregar
sudo nginx -t && sudo systemctl reload nginx
```

### Corrigir Banco de Dados

```bash
# 1. Verificar arquivo .env
cat /var/www/aula7/.env

# 2. Se estiver usando 'root', corrigir para 'app_user'
sudo tee /var/www/aula7/.env > /dev/null <<'EOF'
DB_HOST=localhost
DB_NAME=aula_php_mvc
DB_USER=app_user
DB_PASS=YBeukFGtazjbCsAw2025
DB_CHARSET=utf8mb4
APP_ENV=production
APP_DEBUG=false
APP_URL=http://191.252.93.136
LOG_LEVEL=info
LOG_FILE=logs/app.log
EOF

# 3. Verificar se o usuário app_user existe no MySQL
mysql -u root -p -e "SELECT User, Host FROM mysql.user WHERE User='app_user';"

# 4. Se não existir, criar:
mysql -u root -p <<'MYSQL'
CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'YBeukFGtazjbCsAw2025';
GRANT ALL PRIVILEGES ON aula_php_mvc.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
MYSQL
```

### Verificar Permissões

```bash
# Ajustar permissões
sudo chown -R www-data:www-data /var/www/aula7
sudo chmod -R 755 /var/www/aula7
sudo chmod -R 775 /var/www/aula7/logs
```

### Testar

```bash
# Testar localmente
curl -I http://localhost/login

# Ver logs
sudo tail -20 /var/log/nginx/aula7_error.log
sudo tail -20 /var/www/aula7/logs/app.log
```

## Comandos Rápidos (Tudo de uma vez)

```bash
cd /var/www/aula7

# Corrigir .env
sudo tee .env > /dev/null <<'EOF'
DB_HOST=localhost
DB_NAME=aula_php_mvc
DB_USER=app_user
DB_PASS=YBeukFGtazjbCsAw2025
DB_CHARSET=utf8mb4
APP_ENV=production
APP_DEBUG=false
APP_URL=http://191.252.93.136
LOG_LEVEL=info
LOG_FILE=logs/app.log
EOF

# Corrigir Nginx
sudo tee /etc/nginx/sites-available/aula7 > /dev/null <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/aula7;
    index index.php;
    access_log /var/log/nginx/aula7_access.log;
    error_log /var/log/nginx/aula7_error.log;
    client_max_body_size 20M;
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
    location ~ /\. { deny all; access_log off; log_not_found off; }
    location ~ ^/(vendor|config|database|tests|\.git|scripts|\.github) { deny all; access_log off; log_not_found off; }
}
EOF

sudo ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

# Permissões
sudo chown -R www-data:www-data /var/www/aula7
sudo chmod -R 755 /var/www/aula7

# Testar
curl http://localhost/login
```

