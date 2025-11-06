# 🔧 Como Corrigir o Erro SSH no GitHub Actions

## ❌ Erro Atual

```
Load key "/home/runner/.ssh/id_rsa": error in libcrypto
Permission denied (publickey, password)
```

## 🔍 Causas Possíveis

1. **Chave privada não configurada** no GitHub Secrets
2. **Formato incorreto da chave** (quebras de linha perdidas)
3. **Chave pública não está no servidor** (`authorized_keys`)
4. **Nome do secret incorreto** (deve ser `VPS_SSH_PRIVATE_KEY`)

---

## ✅ Solução Passo a Passo

### Passo 1: Gerar Chave SSH para Deploy

**No servidor VPS:**

```bash
ssh root@191.252.93.136

# Gerar chave dedicada para deploy
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy_key

# Quando pedir senha, pressione ENTER (sem senha)
# Quando pedir confirmação, pressione ENTER novamente

# Adicionar chave pública ao authorized_keys
cat ~/.ssh/github_deploy_key.pub >> ~/.ssh/authorized_keys

# Ajustar permissões
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh

# Mostrar chave privada para copiar
cat ~/.ssh/github_deploy_key
```

**Copie TODO o conteúdo** incluindo:
```
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

### Passo 2: Configurar Secrets no GitHub

1. Acesse: **Seu Repositório → Settings → Secrets and variables → Actions**
2. Clique em **"New repository secret"**

#### Secret 1: VPS_SSH_PRIVATE_KEY

- **Name**: `VPS_SSH_PRIVATE_KEY`
- **Value**: Cole a chave privada completa (incluindo `-----BEGIN` e `-----END`)
- ⚠️ **IMPORTANTE**: Cole exatamente como está, sem adicionar ou remover espaços

#### Secret 2: VPS_HOST

- **Name**: `VPS_HOST`
- **Value**: `191.252.93.136`

#### Secret 3: VPS_USER

- **Name**: `VPS_USER`
- **Value**: `root`

#### Secret 4: VPS_PATH

- **Name**: `VPS_PATH`
- **Value**: `/var/www/aula7`

### Passo 3: Verificar Chave no Servidor

```bash
# Conectar ao servidor
ssh root@191.252.93.136

# Verificar se a chave pública está no authorized_keys
cat ~/.ssh/authorized_keys | grep github-actions-deploy

# Se não aparecer nada, adicione novamente:
cat ~/.ssh/github_deploy_key.pub >> ~/.ssh/authorized_keys
```

### Passo 4: Testar Conexão Manualmente

**No seu computador local:**

```bash
# Criar arquivo temporário com a chave
cat > /tmp/test_key << 'EOF'
-----BEGIN OPENSSH PRIVATE KEY-----
(cole sua chave privada aqui)
-----END OPENSSH PRIVATE KEY-----
EOF

chmod 600 /tmp/test_key

# Testar conexão
ssh -i /tmp/test_key root@191.252.93.136 "echo '✅ Conexão funcionando!'"

# Limpar
rm /tmp/test_key
```

---

## 🔍 Troubleshooting

### Erro: "error in libcrypto"

**Causa**: Formato incorreto da chave privada

**Solução**:
- Certifique-se de copiar a chave EXATAMENTE como está
- Não remova espaços ou quebras de linha
- Deve incluir `-----BEGIN` e `-----END`

### Erro: "Permission denied (publickey)"

**Causa 1**: Chave pública não está no servidor

**Solução**:
```bash
# No servidor
cat ~/.ssh/github_deploy_key.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

**Causa 2**: Permissões incorretas no servidor

**Solução**:
```bash
# No servidor
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
chown root:root ~/.ssh/authorized_keys
```

### Erro: "Host key verification failed"

**Solução**: O workflow já tem `StrictHostKeyChecking=no`, mas se persistir:
```bash
# No servidor
ssh-keygen -R 191.252.93.136
```

### Verificar Secret no GitHub

1. Vá em **Settings → Secrets → Actions**
2. Verifique se `VPS_SSH_PRIVATE_KEY` existe
3. ⚠️ **Não** clique em "Update" para ver o conteúdo - ele não mostra
4. Se precisar, delete e crie novamente

---

## ✅ Checklist de Verificação

- [ ] Chave SSH gerada no servidor (`github_deploy_key`)
- [ ] Chave pública adicionada ao `authorized_keys`
- [ ] Permissões corretas no servidor (700 para `.ssh`, 600 para `authorized_keys`)
- [ ] Secret `VPS_SSH_PRIVATE_KEY` criado no GitHub
- [ ] Secret `VPS_HOST` configurado (`191.252.93.136`)
- [ ] Secret `VPS_USER` configurado (`root`)
- [ ] Secret `VPS_PATH` configurado (`/var/www/aula7`)
- [ ] Teste de conexão manual funcionando
- [ ] Workflow atualizado e commitado

---

## 🚀 Após Configurar

1. Faça um novo commit (ou force re-run do workflow)
2. Vá em **Actions** no GitHub
3. Veja o workflow executando
4. Verifique os logs para confirmar sucesso

---

**Dica**: Se ainda não funcionar, verifique os logs do GitHub Actions linha por linha para identificar exatamente onde está falhando.

