#!/bin/bash

# Script para executar testes com relatórios

echo "=== EXECUTANDO TESTES COM RELATÓRIOS ==="
echo ""

# Criar diretório de relatórios
mkdir -p reports

# 1. Relatório TestDox (formato legível)
echo "1. Gerando relatório TestDox (texto)..."
./vendor/bin/phpunit tests/ --testdox-text reports/testdox.txt
echo "✓ Relatório salvo em: reports/testdox.txt"
echo ""

# 2. Relatório TestDox HTML (visual)
echo "2. Gerando relatório TestDox HTML..."
./vendor/bin/phpunit tests/ --testdox-html reports/testdox.html
echo "✓ Relatório HTML salvo em: reports/testdox.html"
echo ""

# 3. Relatório JUnit XML (para CI/CD)
echo "3. Gerando relatório JUnit XML..."
./vendor/bin/phpunit tests/ --log-junit reports/junit.xml
echo "✓ Relatório JUnit salvo em: reports/junit.xml"
echo ""

# 4. Cobertura de código HTML
echo "4. Gerando relatório de cobertura de código..."
./vendor/bin/phpunit tests/ --coverage-html reports/coverage
echo "✓ Relatório de cobertura salvo em: reports/coverage/index.html"
echo ""

# 5. Cobertura de código texto
echo "5. Gerando resumo de cobertura (texto)..."
./vendor/bin/phpunit tests/ --coverage-text --coverage-text=reports/coverage.txt
echo "✓ Resumo de cobertura salvo em: reports/coverage.txt"
echo ""

echo "=== RELATÓRIOS GERADOS ==="
echo "📄 Texto legível: reports/testdox.txt"
echo "🌐 HTML visual: reports/testdox.html"
echo "📊 Cobertura HTML: reports/coverage/index.html"
echo "📋 Resumo cobertura: reports/coverage.txt"
echo ""
echo "Para ver no navegador (se estiver no servidor):"
echo "  Abra: http://191.252.93.136/reports/testdox.html"
echo "  Ou: http://191.252.93.136/reports/coverage/index.html"

