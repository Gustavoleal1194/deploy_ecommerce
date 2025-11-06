#!/bin/bash

# Script para corrigir configuração do Nginx no servidor

echo "=== DIAGNÓSTICO E CORREÇÃO DO NGINX ==="
echo ""

# Verificar se o arquivo de configuração existe
echo "1. Verificando arquivo de configuração..."
if [ -f /etc/nginx/sites-available/aula7 ]; then
    echo "   ✓ Arquivo /etc/nginx/sites-available/aula7 existe"
    echo "   Conteúdo:"
    cat /etc/nginx/sites-available/aula7
else
    echo "   ✗ Arquivo não existe! Criando..."
    cat > /etc/nginx/sites-available/aula7 <<'EOF'
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
    echo "   ✓ Arquivo criado"
fi

echo ""
echo "2. Verificando se o site está habilitado..."
if [ -L /etc/nginx/sites-enabled/aula7 ]; then
    echo "   ✓ Symlink existe"
else
    echo "   ✗ Symlink não existe! Criando..."
    ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7
    echo "   ✓ Symlink criado"
fi

echo ""
echo "3. Verificando site padrão..."
if [ -f /etc/nginx/sites-enabled/default ]; then
    echo "   ⚠ Site padrão ainda está ativo! Desabilitando..."
    rm -f /etc/nginx/sites-enabled/default
    echo "   ✓ Site padrão desabilitado"
else
    echo "   ✓ Site padrão já está desabilitado"
fi

echo ""
echo "4. Verificando se o diretório do projeto existe..."
if [ -d /var/www/aula7 ]; then
    echo "   ✓ Diretório /var/www/aula7 existe"
    ls -la /var/www/aula7/index.php 2>/dev/null && echo "   ✓ index.php existe" || echo "   ✗ index.php NÃO existe!"
else
    echo "   ✗ Diretório não existe!"
fi

echo ""
echo "5. Verificando PHP-FPM..."
if systemctl is-active --quiet php8.2-fpm; then
    echo "   ✓ PHP-FPM está rodando"
else
    echo "   ✗ PHP-FPM NÃO está rodando!"
    systemctl status php8.2-fpm
fi

echo ""
echo "6. Testando configuração do Nginx..."
nginx -t

if [ $? -eq 0 ]; then
    echo "   ✓ Configuração válida"
    echo ""
    echo "7. Recarregando Nginx..."
    systemctl reload nginx
    echo "   ✓ Nginx recarregado"
    echo ""
    echo "=== CONCLUÍDO ==="
    echo "Acesse: http://191.252.93.136/login"
else
    echo "   ✗ Erro na configuração! Verifique os logs acima"
    exit 1
fi

