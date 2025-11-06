#!/bin/bash

echo "=== TESTANDO TODOS OS ENDPOINTS DA API ==="
echo ""

cd /var/www/aula7

# Criar diretório de relatórios
mkdir -p reports

# Executar testes de endpoints
echo "Executando testes de endpoints..."
./vendor/bin/phpunit tests/Integration/EndpointTest.php --testdox-text reports/endpoints-testdox.txt

# O relatório será gerado automaticamente pelo teste
echo ""
echo "=== RELATÓRIOS GERADOS ==="
echo ""

if [ -f relatorio-endpoints.txt ]; then
    echo "📄 Relatório texto: relatorio-endpoints.txt"
    echo "   (Mostra todas as respostas dos endpoints)"
    echo ""
    echo "Primeiras linhas do relatório:"
    head -30 relatorio-endpoints.txt
    echo ""
    echo "..."
    echo ""
fi

if [ -f relatorio-endpoints.json ]; then
    echo "📊 Relatório JSON: relatorio-endpoints.json"
    echo "   (Dados estruturados para análise)"
    echo ""
fi

echo "📋 TestDox: reports/endpoints-testdox.txt"
echo ""
echo "Para ver o relatório completo:"
echo "  cat relatorio-endpoints.txt"
echo "  ou"
echo "  cat relatorio-endpoints.json | jq"

