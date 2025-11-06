# 🔧 Como Corrigir o Nginx - 404 Not Found

## Problema
Ao acessar `http://191.252.93.136/login`, aparece:
- **404 Not Found** ou
- **Página padrão do Nginx** ("Welcome to nginx!")

## Solução Rápida

Execute estes comandos **no servidor VPS** via SSH:

```bash
# 1. Verificar se o arquivo de configuração existe
cat /etc/nginx/sites-available/aula7

# 2. Se não existir, criar o arquivo
sudo nano /etc/nginx/sites-available/aula7
```

Cole este conteúdo:

```nginx
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
```

Salve (Ctrl+O, Enter, Ctrl+X).

Agora execute:

```bash
# 3. Habilitar o site (criar symlink)
sudo ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7

# 4. Desabilitar site padrão do Nginx
sudo rm -f /etc/nginx/sites-enabled/default

# 5. Testar configuração
sudo nginx -t

# 6. Se o teste passar, recarregar Nginx
sudo systemctl reload nginx

# 7. Verificar status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
```

## Verificações

```bash
# Verificar se o symlink foi criado
ls -la /etc/nginx/sites-enabled/

# Deve mostrar: aula7 -> /etc/nginx/sites-available/aula7

# Verificar se o index.php existe
ls -la /var/www/aula7/index.php

# Verificar logs de erro
sudo tail -20 /var/log/nginx/aula7_error.log
sudo tail -20 /var/log/nginx/error.log
```

## Solução Automática

Se preferir, você pode usar o script de correção:

```bash
cd /var/www/aula7
chmod +x scripts/corrigir-nginx.sh
sudo ./scripts/corrigir-nginx.sh
```

## Teste Final

Após executar os comandos, acesse:
- `http://191.252.93.136/login` - Deve mostrar a página de login
- `http://191.252.93.136` - Deve redirecionar ou mostrar a página inicial

Se ainda não funcionar, verifique:

1. **PHP-FPM está rodando?**
   ```bash
   sudo systemctl status php8.2-fpm
   sudo systemctl start php8.2-fpm
   ```

2. **O socket do PHP-FPM existe?**
   ```bash
   ls -la /var/run/php/php8.2-fpm.sock
   ```

3. **Permissões corretas?**
   ```bash
   sudo chown -R www-data:www-data /var/www/aula7
   sudo chmod -R 755 /var/www/aula7
   ```

4. **Arquivo index.php existe?**
   ```bash
   ls -la /var/www/aula7/index.php
   ```

