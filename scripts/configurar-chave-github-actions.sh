#!/bin/bash

# Script para configurar chave SSH sem passphrase para GitHub Actions
# Execute este script NO SERVIDOR VPS

set -e

echo "🔑 Configurando chave SSH para GitHub Actions..."
echo ""

# Diretório SSH
SSH_DIR="$HOME/.ssh"
KEY_FILE="$SSH_DIR/github_deploy"

# Criar diretório .ssh se não existir
mkdir -p "$SSH_DIR"
chmod 700 "$SSH_DIR"

# Gerar nova chave SSH sem passphrase
echo "📝 Gerando nova chave SSH (ed25519, sem passphrase)..."
ssh-keygen -t ed25519 -C "github-actions-deploy" -f "$KEY_FILE" -N "" -q

# Configurar permissões
chmod 600 "$KEY_FILE"
chmod 644 "${KEY_FILE}.pub"

# Adicionar chave pública ao authorized_keys
echo "➕ Adicionando chave pública ao authorized_keys..."
if ! grep -q "$(cat ${KEY_FILE}.pub)" "$SSH_DIR/authorized_keys" 2>/dev/null; then
    cat "${KEY_FILE}.pub" >> "$SSH_DIR/authorized_keys"
    chmod 600 "$SSH_DIR/authorized_keys"
    echo "✅ Chave pública adicionada ao authorized_keys"
else
    echo "⚠️  Chave pública já existe no authorized_keys"
fi

# Testar conexão local
echo ""
echo "🧪 Testando conexão SSH local..."
if ssh -i "$KEY_FILE" -o StrictHostKeyChecking=no -o BatchMode=yes localhost "echo '✅ Conexão OK'" 2>/dev/null; then
    echo "✅ Teste de conexão local bem-sucedido!"
else
    echo "⚠️  Teste local falhou, mas a chave foi gerada"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📋 CHAVE PRIVADA (copie TODO o conteúdo abaixo):"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
cat "$KEY_FILE"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📝 Próximos passos:"
echo "1. Copie TODO o conteúdo da chave privada acima (incluindo BEGIN e END)"
echo "2. Acesse: https://github.com/Gustavoleal1194/deploy_ecommerce/settings/secrets/actions"
echo "3. Edite ou crie o secret: VPS_SSH_PRIVATE_KEY"
echo "4. Cole a chave privada completa"
echo "5. Salve"
echo ""
echo "✅ Configuração concluída no servidor!"

