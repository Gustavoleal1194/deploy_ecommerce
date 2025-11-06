# 🚀 Executar no Servidor VPS

## 📋 Passo a Passo

### 1. Conectar ao Servidor

```bash
ssh root@191.252.93.136
```

### 2. Baixar e Executar o Script

```bash
# Opção 1: Via curl (se disponível)
curl -o /tmp/configurar-chave.sh https://raw.githubusercontent.com/Gustavoleal1194/deploy_ecommerce/main/scripts/configurar-chave-github-actions.sh

# Opção 2: Copiar o conteúdo manualmente
# Criar o arquivo:
nano /tmp/configurar-chave.sh
# Cole o conteúdo do script e salve (Ctrl+X, Y, Enter)

# Dar permissão de execução
chmod +x /tmp/configurar-chave.sh

# Executar
/tmp/configurar-chave.sh
```

### 3. Copiar a Chave Privada

O script vai exibir a chave privada. **Copie TODO o conteúdo**, incluindo:
```
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

### 4. Adicionar ao GitHub Secrets

1. Acesse: https://github.com/Gustavoleal1194/deploy_ecommerce/settings/secrets/actions
2. Edite ou crie: `VPS_SSH_PRIVATE_KEY`
3. Cole a chave privada completa
4. Salve

### 5. Testar

Re-execute o workflow no GitHub Actions. Deve funcionar agora! 🎉

---

## 🔄 Alternativa: Comandos Manuais

Se preferir fazer manualmente:

```bash
# No servidor
ssh-keygen -t ed25519 -C "github-actions" -f ~/.ssh/github_deploy -N ""
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
chmod 600 ~/.ssh/github_deploy

# Exibir chave privada
cat ~/.ssh/github_deploy
```

Depois copie a chave e adicione ao GitHub Secrets.

