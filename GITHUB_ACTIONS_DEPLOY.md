# 🚀 Deploy Automático via GitHub Actions

## ⚠️ ALERTA DE SEGURANÇA CRÍTICO

**NUNCA coloque sua chave privada SSH pessoal no GitHub Actions!**

Isso expõe seu acesso ao servidor e é um risco de segurança grave.

---

## ✅ Solução Correta: SSH Deploy Key

Para deploy automático, você precisa criar uma **chave SSH específica para deploy** (não a sua chave pessoal).

### Passo 1: Gerar SSH Deploy Key no Servidor

Conecte-se ao servidor VPS e execute:

```bash
# Conectar ao servidor
ssh root@191.252.93.136

# Gerar par de chaves DEDICADO para deploy
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy

# Isso criará:
# - ~/.ssh/github_actions_deploy      (chave privada)
# - ~/.ssh/github_actions_deploy.pub   (chave pública)
```

### Passo 2: Adicionar Chave Pública no Servidor

```bash
# Adicionar chave pública ao authorized_keys
cat ~/.ssh/github_actions_deploy.pub >> ~/.ssh/authorized_keys

# Ajustar permissões
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

### Passo 3: Copiar Chave Privada (apenas o conteúdo)

```bash
# No servidor, mostrar a chave privada
cat ~/.ssh/github_actions_deploy
```

**Copie TODO o conteúdo** (incluindo `-----BEGIN` e `-----END`)

### Passo 4: Configurar no GitHub

1. Vá em: **Seu Repositório → Settings → Secrets and variables → Actions**
2. Clique em **"New repository secret"**
3. Nome: `VPS_SSH_PRIVATE_KEY`
4. Valor: Cole a chave privada completa (a que você copiou)
5. Adicione também:
   - `VPS_HOST`: `191.252.93.136`
   - `VPS_USER`: `root`
   - `VPS_PATH`: `/var/www/aula7`

---

## 🔧 Configuração do GitHub Actions Workflow

O workflow já está configurado em `.github/workflows/ci.yml`, mas aqui está o passo de deploy:

```yaml
deploy:
  name: Deploy para VPS
  runs-on: ubuntu-latest
  needs: [test, security]
  if: github.ref == 'refs/heads/main' && github.event_name == 'push'
  
  steps:
    - name: Checkout código
      uses: actions/checkout@v4

    - name: Configurar SSH
      uses: webfactory/ssh-agent@v0.7.0
      with:
        ssh-private-key: ${{ secrets.VPS_SSH_PRIVATE_KEY }}

    - name: Deploy via SSH
      run: |
        ssh -o StrictHostKeyChecking=no ${{ secrets.VPS_USER }}@${{ secrets.VPS_HOST }} << 'EOF'
          cd ${{ secrets.VPS_PATH }}
          git pull origin main
          composer install --no-dev --optimize-autoloader
          composer dump-autoload
          # Recarregar serviços se necessário
          # systemctl reload php8.2-fpm
        EOF
```

---

## 🔐 Alternativa: Usar SSH Key Deploy (Mais Seguro)

### Gerar chave localmente e adicionar no GitHub

```bash
# No seu computador local (NÃO no servidor)
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy_key
```

**Chave Pública** → Adicionar no servidor (`~/.ssh/authorized_keys`)  
**Chave Privada** → Adicionar como SECRET no GitHub

---

## 📝 Checklist de Configuração

- [ ] Gerar chave SSH específica para deploy (não usar chave pessoal)
- [ ] Adicionar chave pública no servidor (`authorized_keys`)
- [ ] Adicionar chave privada como SECRET no GitHub
- [ ] Configurar variáveis de ambiente (VPS_HOST, VPS_USER, VPS_PATH)
- [ ] Testar workflow manualmente
- [ ] Verificar logs de deploy

---

## 🧪 Testar Deploy Manualmente

1. Faça um commit pequeno
2. Push para `main`
3. Vá em **Actions** no GitHub
4. Veja o workflow executando
5. Verifique se o deploy funcionou no servidor

---

## 🔍 Troubleshooting

### Erro: "Permission denied (publickey)"

- Verifique se a chave privada está correta no SECRET
- Verifique se a chave pública está no `authorized_keys` do servidor
- Verifique permissões: `chmod 600 ~/.ssh/authorized_keys`

### Erro: "Host key verification failed"

- Adicione `-o StrictHostKeyChecking=no` no comando SSH
- Ou adicione o host key conhecido

### Erro: "git pull failed"

- Verifique se o diretório `/var/www/aula7` é um repositório Git
- Verifique permissões: `chown -R www-data:www-data /var/www/aula7`

---

## ✨ Benefícios desta Abordagem

✅ **Segurança**: Chave dedicada apenas para deploy  
✅ **Rastreabilidade**: Logs de deploy no GitHub Actions  
✅ **Automação**: Deploy automático após push  
✅ **Rollback**: Fácil reverter mudanças via Git  
✅ **Isolamento**: Chave separada da sua chave pessoal

---

**IMPORTANTE**: A chave privada que você vai usar no GitHub Actions é diferente da sua chave pessoal. É uma chave gerada especificamente para este propósito.

