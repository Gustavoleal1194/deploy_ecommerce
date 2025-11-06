# 🔐 Configurar Secrets no GitHub Actions

## 📋 Secrets Necessários

Você precisa configurar os seguintes secrets no GitHub:

1. **VPS_SSH_PRIVATE_KEY** - Chave privada SSH para deploy
2. **VPS_HOST** - IP ou hostname do servidor
3. **VPS_USER** - Usuário SSH (ex: `root`)
4. **VPS_PATH** - Caminho do projeto no servidor

---

## 🚀 Passo a Passo

### 1. Gerar Chave SSH para Deploy

**⚠️ IMPORTANTE**: Não use sua chave pessoal! Crie uma chave específica.

#### Opção A: Gerar no Servidor (Recomendado)

Conecte-se ao servidor:

```bash
ssh root@191.252.93.136

# Gerar chave dedicada para deploy
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy

# Quando pedir senha, pressione ENTER (sem senha)

# Adicionar chave pública ao authorized_keys
cat ~/.ssh/github_actions_deploy.pub >> ~/.ssh/authorized_keys

# Mostrar chave privada para copiar
cat ~/.ssh/github_actions_deploy
```

#### Opção B: Gerar Localmente

```powershell
# No PowerShell ou Git Bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy

# Copiar chave pública para o servidor
type ~/.ssh/github_actions_deploy.pub | ssh root@191.252.93.136 "cat >> ~/.ssh/authorized_keys"

# Mostrar chave privada
cat ~/.ssh/github_actions_deploy
```

### 2. Adicionar Secrets no GitHub

1. Acesse seu repositório no GitHub
2. Vá em **Settings** → **Secrets and variables** → **Actions**
3. Clique em **"New repository secret"**

#### Secret 1: VPS_SSH_PRIVATE_KEY

- **Name**: `VPS_SSH_PRIVATE_KEY`
- **Value**: Cole a chave privada completa (incluindo `-----BEGIN` e `-----END`)

```
-----BEGIN OPENSSH PRIVATE KEY-----
...
(conteúdo completo da chave privada)
...
-----END OPENSSH PRIVATE KEY-----
```

#### Secret 2: VPS_HOST

- **Name**: `VPS_HOST`
- **Value**: `191.252.93.136`

#### Secret 3: VPS_USER

- **Name**: `VPS_USER`
- **Value**: `root`

#### Secret 4: VPS_PATH

- **Name**: `VPS_PATH`
- **Value**: `/var/www/aula7`

---

## ✅ Verificar Configuração

### Testar SSH Manualmente

```bash
# Testar conexão SSH com a chave
ssh -i ~/.ssh/github_actions_deploy root@191.252.93.136

# Se funcionar, você está conectado!
```

### Testar Workflow

1. Faça um pequeno commit (ex: adicionar comentário)
2. Push para `main`:
   ```bash
   git add .
   git commit -m "test: verificar deploy automático"
   git push origin main
   ```
3. Vá em **Actions** no GitHub
4. Veja o workflow executando
5. Verifique os logs do deploy

---

## 🔍 Troubleshooting

### Erro: "Permission denied (publickey)"

**Solução:**
- Verifique se a chave privada está correta no SECRET
- Verifique se a chave pública está no `authorized_keys` do servidor
- Verifique permissões: `chmod 600 ~/.ssh/authorized_keys`

### Erro: "Host key verification failed"

**Solução:**
- O workflow já tem `StrictHostKeyChecking=no`
- Se persistir, adicione o host key manualmente

### Erro: "git pull failed"

**Solução:**
- Verifique se `/var/www/aula7` é um repositório Git
- Verifique permissões: `chown -R www-data:www-data /var/www/aula7`
- Verifique se o Git está configurado: `git config --global --add safe.directory /var/www/aula7`

### Erro: "composer: command not found"

**Solução:**
- Verifique se o Composer está instalado: `which composer`
- Se não estiver, instale: `curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer`

---

## 📝 Checklist Final

- [ ] Chave SSH gerada e adicionada ao servidor
- [ ] Secret `VPS_SSH_PRIVATE_KEY` configurado
- [ ] Secret `VPS_HOST` configurado
- [ ] Secret `VPS_USER` configurado
- [ ] Secret `VPS_PATH` configurado
- [ ] Teste de conexão SSH manual funcionando
- [ ] Workflow testado com commit pequeno

---

## 🔒 Segurança

✅ **Fazer:**
- Usar chave SSH específica para deploy
- Rotar chaves periodicamente
- Usar ed25519 (mais seguro que RSA)
- Limitar permissões da chave no servidor

❌ **NÃO fazer:**
- Usar sua chave pessoal SSH
- Commitear chaves no repositório
- Compartilhar secrets com outros
- Usar senhas fracas

---

**Pronto!** Agora seu deploy automático está configurado. 🎉

