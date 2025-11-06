#!/bin/bash

echo "=== DIAGNÓSTICO ERRO 500 - /login ==="
echo ""

echo "1. Verificando logs de erro do Nginx:"
sudo tail -50 /var/log/nginx/aula7_error.log 2>/dev/null || tail -50 /var/log/nginx/error.log

echo ""
echo "2. Verificando logs do PHP-FPM:"
sudo tail -50 /var/log/php8.2-fpm.log 2>/dev/null || echo "Log do PHP-FPM não encontrado"

echo ""
echo "3. Verificando se o arquivo login.php existe:"
ls -la /var/www/aula7/app/Views/auth/login.php

echo ""
echo "4. Verificando permissões do diretório:"
ls -ld /var/www/aula7

echo ""
echo "5. Verificando se vendor/autoload.php existe:"
ls -la /var/www/aula7/vendor/autoload.php 2>/dev/null && echo "✓ Existe" || echo "✗ NÃO existe"

echo ""
echo "6. Testando se PHP consegue carregar o autoloader:"
php -r "require '/var/www/aula7/vendor/autoload.php'; echo 'Autoloader OK' . PHP_EOL;" 2>&1

echo ""
echo "7. Testando se a classe Render existe:"
php -r "require '/var/www/aula7/vendor/autoload.php'; echo (class_exists('App\Views\Render') ? 'Render existe' : 'Render NÃO existe') . PHP_EOL;" 2>&1

echo ""
echo "8. Testando se AuthController existe:"
php -r "require '/var/www/aula7/vendor/autoload.php'; echo (class_exists('App\Controllers\AuthController') ? 'AuthController existe' : 'AuthController NÃO existe') . PHP_EOL;" 2>&1

echo ""
echo "9. Verificando sintaxe do index.php:"
php -l /var/www/aula7/index.php

echo ""
echo "10. Verificando sintaxe do login.php:"
php -l /var/www/aula7/app/Views/auth/login.php

echo ""
echo "=== FIM DO DIAGNÓSTICO ==="

