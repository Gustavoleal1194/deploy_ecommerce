#!/bin/bash

# Script de Deploy para VPS LocawWeb
# Uso: bash scripts/deploy-vps.sh

set -e  # Parar em caso de erro

echo "🚀 Iniciando deploy no VPS..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Variáveis (ajustar conforme necessário)
PROJECT_DIR="/var/www/aula7"
BACKUP_DIR="/var/backups/aula7"
DATE=$(date +%Y%m%d_%H%M%S)

# Função para verificar se comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verificar se está rodando como root ou com sudo
if [ "$EUID" -ne 0 ]; then 
    echo -e "${YELLOW}⚠️  Alguns comandos podem precisar de sudo${NC}"
fi

echo "📋 Verificando pré-requisitos..."

# Verificar PHP
if command_exists php; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2)
    echo -e "${GREEN}✅ PHP instalado: $PHP_VERSION${NC}"
else
    echo -e "${RED}❌ PHP não encontrado. Instale PHP 8.2+${NC}"
    exit 1
fi

# Verificar Composer
if command_exists composer; then
    echo -e "${GREEN}✅ Composer instalado${NC}"
else
    echo -e "${RED}❌ Composer não encontrado${NC}"
    exit 1
fi

# Verificar MySQL
if command_exists mysql; then
    echo -e "${GREEN}✅ MySQL instalado${NC}"
else
    echo -e "${YELLOW}⚠️  MySQL não encontrado (pode estar instalado como MariaDB)${NC}"
fi

# Verificar se diretório do projeto existe
if [ ! -d "$PROJECT_DIR" ]; then
    echo -e "${YELLOW}⚠️  Diretório $PROJECT_DIR não existe. Criando...${NC}"
    mkdir -p "$PROJECT_DIR"
fi

# Backup antes de atualizar
if [ -d "$PROJECT_DIR" ] && [ "$(ls -A $PROJECT_DIR)" ]; then
    echo "💾 Fazendo backup..."
    mkdir -p "$BACKUP_DIR"
    tar -czf "$BACKUP_DIR/backup_$DATE.tar.gz" -C "$PROJECT_DIR" .
    echo -e "${GREEN}✅ Backup criado: $BACKUP_DIR/backup_$DATE.tar.gz${NC}"
fi

# Entrar no diretório do projeto
cd "$PROJECT_DIR"

# Se usar Git
if [ -d ".git" ]; then
    echo "📥 Atualizando código via Git..."
    git pull origin main || git pull origin master
    echo -e "${GREEN}✅ Código atualizado${NC}"
else
    echo -e "${YELLOW}⚠️  Não é um repositório Git. Pule esta etapa se estiver usando FTP.${NC}"
fi

# Instalar/Atualizar dependências
echo "📦 Instalando dependências..."
composer install --no-dev --optimize-autoloader --no-interaction
echo -e "${GREEN}✅ Dependências instaladas${NC}"

# Verificar arquivo .env
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}⚠️  Arquivo .env não encontrado${NC}"
    if [ -f ".env.example" ]; then
        echo "📝 Copiando .env.example para .env"
        cp .env.example .env
        echo -e "${YELLOW}⚠️  IMPORTANTE: Edite o arquivo .env com as configurações do servidor!${NC}"
        echo "   nano $PROJECT_DIR/.env"
    fi
else
    echo -e "${GREEN}✅ Arquivo .env encontrado${NC}"
fi

# Criar diretório de logs
echo "📁 Configurando diretórios..."
mkdir -p logs
chmod 775 logs
echo -e "${GREEN}✅ Diretório de logs configurado${NC}"

# Configurar permissões
echo "🔐 Configurando permissões..."
if [ "$EUID" -eq 0 ]; then
    chown -R www-data:www-data "$PROJECT_DIR"
fi
chmod -R 755 "$PROJECT_DIR"
chmod -R 775 logs
echo -e "${GREEN}✅ Permissões configuradas${NC}"

# Limpar cache (se houver)
if [ -d "cache" ]; then
    echo "🧹 Limpando cache..."
    rm -rf cache/*
    echo -e "${GREEN}✅ Cache limpo${NC}"
fi

# Verificar banco de dados
echo "🗄️  Verificando banco de dados..."
if [ -f "database/schema.sql" ]; then
    echo -e "${YELLOW}ℹ️  Schema SQL encontrado. Importe manualmente se necessário:${NC}"
    echo "   mysql -u usuario -p nome_banco < database/schema.sql"
else
    echo -e "${YELLOW}⚠️  Arquivo schema.sql não encontrado${NC}"
fi

# Reiniciar serviços (se necessário)
echo "🔄 Verificando serviços..."

# Nginx
if systemctl is-active --quiet nginx; then
    echo "🔄 Testando configuração do Nginx..."
    nginx -t && systemctl reload nginx
    echo -e "${GREEN}✅ Nginx recarregado${NC}"
fi

# Apache
if systemctl is-active --quiet apache2; then
    echo "🔄 Recarregando Apache..."
    systemctl reload apache2
    echo -e "${GREEN}✅ Apache recarregado${NC}"
fi

# PHP-FPM
if systemctl is-active --quiet php8.2-fpm || systemctl is-active --quiet php-fpm; then
    echo "🔄 Recarregando PHP-FPM..."
    systemctl reload php8.2-fpm 2>/dev/null || systemctl reload php-fpm
    echo -e "${GREEN}✅ PHP-FPM recarregado${NC}"
fi

# Verificação final
echo ""
echo "✅ Deploy concluído!"
echo ""
echo "📋 Checklist pós-deploy:"
echo "   [ ] Testar acesso à aplicação"
echo "   [ ] Verificar login"
echo "   [ ] Testar CRUD de produtos"
echo "   [ ] Testar CRUD de categorias"
echo "   [ ] Verificar logs (logs/app.log)"
echo "   [ ] Verificar se não há erros no log do servidor"
echo ""
echo "🌐 Acesse: http://$(hostname -I | awk '{print $1}')/aula7"
echo "   ou: https://seudominio.com.br (se configurado)"
echo ""

