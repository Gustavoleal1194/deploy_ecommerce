#!/bin/bash

# Script de deploy para produção
# Este é um exemplo - adapte conforme sua infraestrutura

set -e  # Parar em caso de erro

echo "🚀 Iniciando deploy..."

# 1. Executar testes
echo "📋 Executando testes..."
composer test

# 2. Verificar qualidade de código
echo "🔍 Verificando qualidade de código..."
composer ci

# 3. Build Docker (se necessário)
if [ "$USE_DOCKER" = "true" ]; then
    echo "🐳 Construindo imagem Docker..."
    docker build -t aula7-mvc:latest .
fi

# 4. Backup do banco de dados (se necessário)
if [ "$BACKUP_DB" = "true" ]; then
    echo "💾 Fazendo backup do banco de dados..."
    # Adicione seu comando de backup aqui
fi

# 5. Deploy
echo "📦 Fazendo deploy..."
# Adicione seus comandos de deploy aqui
# Exemplos:
# - rsync para servidor
# - scp para servidor remoto
# - kubectl apply para Kubernetes
# - etc.

echo "✅ Deploy concluído com sucesso!"

