#!/bin/bash

echo "=== DIAGNÓSTICO COMPLETO DO SERVIDOR ==="
echo ""

echo "1. VERIFICANDO NGINX..."
echo "Sites disponíveis:"
ls -la /etc/nginx/sites-available/ | grep aula7

echo ""
echo "Sites habilitados:"
ls -la /etc/nginx/sites-enabled/

echo ""
echo "Conteúdo do arquivo de configuração:"
cat /etc/nginx/sites-available/aula7

echo ""
echo "2. VERIFICANDO ARQUIVOS DO PROJETO..."
echo "Diretório existe?"
ls -d /var/www/aula7 && echo "✓ Existe" || echo "✗ NÃO existe"

echo ""
echo "Arquivo index.php existe?"
ls -la /var/www/aula7/index.php && echo "✓ Existe" || echo "✗ NÃO existe"

echo ""
echo "Permissões:"
ls -ld /var/www/aula7

echo ""
echo "3. VERIFICANDO PHP-FPM..."
systemctl status php8.2-fpm --no-pager | head -5

echo ""
echo "Socket PHP-FPM existe?"
ls -la /var/run/php/php8.2-fpm.sock && echo "✓ Existe" || echo "✗ NÃO existe"

echo ""
echo "4. VERIFICANDO BANCO DE DADOS..."
echo "Arquivo .env existe?"
if [ -f /var/www/aula7/.env ]; then
    echo "✓ Existe"
    echo "Conteúdo (sem senhas):"
    grep -v "PASS\|PASSWORD" /var/www/aula7/.env
else
    echo "✗ NÃO existe"
fi

echo ""
echo "5. TESTANDO CONEXÃO..."
echo "Testando curl local:"
curl -I http://localhost/login 2>&1 | head -5

echo ""
echo "6. VERIFICANDO LOGS..."
echo "Últimas linhas do log de erro do Nginx:"
tail -10 /var/log/nginx/aula7_error.log 2>/dev/null || echo "Log não encontrado"

echo ""
echo "=== FIM DO DIAGNÓSTICO ==="

