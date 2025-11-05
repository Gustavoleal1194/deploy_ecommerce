#!/bin/bash

# Script para configurar banco de dados de testes

echo "🔧 Configurando banco de dados de testes..."

# Verificar se MySQL está rodando
if ! mysqladmin ping -h localhost -u root --silent 2>/dev/null; then
    echo "❌ MySQL não está rodando. Por favor, inicie o MySQL primeiro."
    exit 1
fi

# Criar banco de dados de teste
mysql -u root -e "CREATE DATABASE IF NOT EXISTS aula_php_mvc_test;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Banco de dados de teste criado: aula_php_mvc_test"
else
    echo "❌ Erro ao criar banco de dados de teste"
    exit 1
fi

# Importar schema
if [ -f "database/schema.sql" ]; then
    mysql -u root aula_php_mvc_test < database/schema.sql 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "✅ Schema importado para banco de teste"
    else
        echo "⚠️  Erro ao importar schema (pode ser normal se já existir)"
    fi
fi

echo "✅ Configuração concluída!"

