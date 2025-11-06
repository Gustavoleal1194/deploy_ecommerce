#!/bin/bash

echo "=== VERIFICAÇÃO COMPLETA NGINX ==="
echo ""

echo "1. Sites habilitados:"
ls -la /etc/nginx/sites-enabled/

echo ""
echo "2. Conteúdo do arquivo de configuração aula7:"
cat /etc/nginx/sites-available/aula7

echo ""
echo "3. Testar configuração:"
nginx -t

echo ""
echo "4. Verificar se o site padrão foi removido:"
if [ -f /etc/nginx/sites-enabled/default ]; then
    echo "⚠ ATENÇÃO: Site padrão ainda está ativo!"
    cat /etc/nginx/sites-enabled/default | head -20
else
    echo "✓ Site padrão foi removido"
fi

echo ""
echo "5. Verificar logs de erro mais recentes:"
sudo tail -30 /var/log/nginx/aula7_error.log

echo ""
echo "6. Testar se PHP consegue carregar as classes:"
php -r "
require '/var/www/aula7/vendor/autoload.php';
echo 'Render existe: ' . (class_exists('App\Views\Render') ? 'SIM' : 'NÃO') . PHP_EOL;
echo 'AuthController existe: ' . (class_exists('App\Controllers\AuthController') ? 'SIM' : 'NÃO') . PHP_EOL;
"

echo ""
echo "7. Testar index.php diretamente:"
php /var/www/aula7/index.php 2>&1 | head -30

echo ""
echo "=== FIM ==="

