#!/bin/bash

# Script de Setup Inicial do VPS para o projeto
# Execute este script UMA VEZ para configurar o servidor

set -e

echo "🔧 Configurando VPS para o projeto..."

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Verificar se está rodando como root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}❌ Este script precisa ser executado como root ou com sudo${NC}"
    exit 1
fi

echo "📦 Atualizando sistema..."
apt update && apt upgrade -y

echo "📦 Instalando PHP 8.2 e extensões..."
apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip

echo "📦 Instalando MySQL..."
apt install -y mysql-server

echo "📦 Instalando Nginx..."
apt install -y nginx

echo "📦 Instalando Composer..."
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

echo "📦 Instalando Git..."
apt install -y git

echo "✅ Instalação concluída!"
echo ""
echo "📋 Próximos passos:"
echo "   1. Configurar MySQL:"
echo "      sudo mysql_secure_installation"
echo "   2. Criar banco de dados:"
echo "      sudo mysql -u root -p"
echo "      CREATE DATABASE aula_php_mvc;"
echo "   3. Configurar Nginx (veja GUIA_DEPLOY_VPS.md)"
echo "   4. Fazer deploy do projeto"
echo ""

