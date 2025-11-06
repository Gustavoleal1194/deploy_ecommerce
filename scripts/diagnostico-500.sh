#!/bin/bash

echo "=== DIAGNÓSTICO ERRO 500 ==="
echo ""

echo "1. Verificando logs do aplicativo..."
if [ -f /var/www/aula7/logs/app.log ]; then
    echo "Últimas 30 linhas do app.log:"
    tail -30 /var/www/aula7/logs/app.log
else
    echo "✗ Arquivo logs/app.log não existe"
fi

echo ""
echo "2. Verificando logs do PHP-FPM..."
sudo tail -30 /var/log/php8.2-fpm.log

echo ""
echo "3. Verificando logs de erro do Nginx..."
sudo tail -30 /var/log/nginx/aula7_error.log

echo ""
echo "4. Verificando se o arquivo .env existe e tem permissões..."
ls -la /var/www/aula7/.env

echo ""
echo "5. Verificando se config/db.php pode ser lido..."
php -r "require '/var/www/aula7/config/db.php'; var_dump(['host' => \$config['host'], 'user' => \$config['user']]);"

echo ""
echo "6. Testando index.php diretamente..."
php /var/www/aula7/index.php 2>&1 | head -20

echo ""
echo "=== FIM DO DIAGNÓSTICO ==="

