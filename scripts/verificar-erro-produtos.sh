#!/bin/bash

echo "=== DIAGNÓSTICO ERRO 500 - /produtos ==="
echo ""

echo "1. Verificar logs de erro do Nginx:"
sudo tail -30 /var/log/nginx/aula7_error.log

echo ""
echo "2. Verificar logs do PHP-FPM:"
sudo tail -30 /var/log/php8.2-fpm.log

echo ""
echo "3. Testar se a classe Render existe:"
php -r "
require '/var/www/aula7/vendor/autoload.php';
var_dump(class_exists('App\Views\Render'));
"

echo ""
echo "4. Verificar se o diretório de views existe:"
ls -la /var/www/aula7/app/Views/Produtos/

echo ""
echo "5. Verificar se o arquivo index.php da view existe:"
ls -la /var/www/aula7/app/Views/Produtos/index.php

echo ""
echo "6. Testar renderização manual:"
php -r "
require '/var/www/aula7/vendor/autoload.php';
use App\Views\Render;
use App\Models\Produto;
use App\Models\Categoria;
try {
    \$produtos = Produto::all();
    \$categorias = Categoria::all();
    echo 'Produtos: ' . count(\$produtos) . PHP_EOL;
    echo 'Categorias: ' . count(\$categorias) . PHP_EOL;
    \$render = new Render();
    echo 'Render criado com sucesso' . PHP_EOL;
} catch (Exception \$e) {
    echo 'ERRO: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "=== FIM ==="

