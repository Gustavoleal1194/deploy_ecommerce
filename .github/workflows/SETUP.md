# 🚀 Guia de Configuração CI/CD - VPS Locaweb

Este guia vai te ajudar a configurar deploy automático para sua VPS na Locaweb.

## 📋 Pré-requisitos

✅ VPS Ubuntu 20.04 na Locaweb (você já tem!)
✅ Acesso SSH configurado
✅ PHP 8.2, MySQL e Nginx instalados
✅ Repositório no GitHub

---

## 🔐 PASSO 1: Configurar Chave SSH para GitHub Actions

### 1.1 No seu computador (PowerShell):

```powershell
# Ver sua chave SSH pública (se já existe)
cat ~/.ssh/id_ed25519.pub

# Se não existir, gerar nova chave
ssh-keygen -t ed25519 -C "github-actions"
# Aperte ENTER 3x (padrão, sem senha)
```

### 1.2 Copiar chave PRIVADA:

```powershell
# Copiar chave PRIVADA (para GitHub Secrets)
cat ~/.ssh/id_ed25519
```

**Copie TODO o conteúdo** (começa com `-----BEGIN OPENSSH PRIVATE KEY-----`)

### 1.3 Adicionar chave PÚBLICA na VPS:

```powershell
# Conectar na VPS
ssh root@SEU_IP_DA_VPS

# Na VPS, adicionar a chave pública
echo "sua_chave_publica_aqui" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
exit
```

---

## 🔑 PASSO 2: Configurar Secrets no GitHub

### 2.1 Acessar o repositório:
1. Ir para: https://github.com/Gustavoleal1194/deploy_ecommerce
2. Clicar em **Settings** (⚙️)
3. No menu lateral: **Secrets and variables** → **Actions**
4. Clicar em **New repository secret**

### 2.2 Adicionar os 4 secrets necessários:

#### Secret 1: `SSH_PRIVATE_KEY`
- **Name**: `SSH_PRIVATE_KEY`
- **Value**: Cole a chave privada que você copiou (todo o conteúdo com BEGIN/END)

#### Secret 2: `VPS_HOST`
- **Name**: `VPS_HOST`
- **Value**: IP da sua VPS (ex: `123.45.67.89`)

#### Secret 3: `VPS_USER`
- **Name**: `VPS_USER`
- **Value**: Usuário SSH (provavelmente `root`)

#### Secret 4: `VPS_PATH`
- **Name**: `VPS_PATH`
- **Value**: Caminho completo no servidor (ex: `/var/www/html/projeto`)

---

## 📂 PASSO 3: Configurar Diretório na VPS

### 3.1 Conectar na VPS:

```bash
ssh root@SEU_IP_DA_VPS
```

### 3.2 Criar estrutura de diretórios:

```bash
# Criar diretório do projeto
mkdir -p /var/www/html/projeto

# Criar diretório de logs
mkdir -p /var/www/html/projeto/logs

# Configurar permissões
chown -R www-data:www-data /var/www/html/projeto
chmod -R 755 /var/www/html/projeto
chmod -R 777 /var/www/html/projeto/logs
```

### 3.3 Criar arquivo .env na VPS:

```bash
cd /var/www/html/projeto
nano .env
```

Adicionar:

```env
DB_HOST=localhost
DB_NAME=aula_php_mvc
DB_USER=seu_usuario_mysql
DB_PASS=sua_senha_mysql
DB_PORT=3306
APP_ENV=production
```

Salvar: `Ctrl+O`, `Enter`, `Ctrl+X`

---

## 🌐 PASSO 4: Configurar Nginx (se ainda não configurou)

### 4.1 Criar configuração do site:

```bash
nano /etc/nginx/sites-available/projeto
```

Adicionar:

```nginx
server {
    listen 80;
    server_name seu_dominio.com.br;  # ou IP da VPS

    root /var/www/html/projeto;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4.2 Ativar site:

```bash
# Criar link simbólico
ln -s /etc/nginx/sites-available/projeto /etc/nginx/sites-enabled/

# Testar configuração
nginx -t

# Reiniciar Nginx
systemctl reload nginx
```

---

## ✅ PASSO 5: Testar o Deploy Automático

### 5.1 Fazer um commit e push:

```powershell
# No seu computador
cd "C:\Users\rodri\OneDrive\Desktop\Sistemas de Informação\Projeto Leal PHP\deploy_ecommerce"

git add .
git commit -m "feat: configurar CI/CD para VPS"
git push origin main
```

### 5.2 Verificar no GitHub:
1. Ir para: https://github.com/Gustavoleal1194/deploy_ecommerce/actions
2. Ver os workflows rodando:
   - ✅ **CI - Testes e Qualidade** (sempre roda)
   - ✅ **CD - Deploy para VPS** (só roda se CI passar)

### 5.3 Acompanhar logs:
- Clique no workflow rodando
- Veja cada etapa sendo executada
- ✅ Verde = sucesso! ❌ Vermelho = erro

---

## 🔧 PASSO 6: Deploy Manual (se necessário)

Se quiser fazer deploy manual sem esperar push:

1. Ir para: Actions → CD - Deploy para VPS
2. Clicar em "Run workflow"
3. Selecionar branch `main`
4. Clicar em "Run workflow"

---

## 📊 RESUMO DO FLUXO AUTOMÁTICO

```
1. Você faz commit e push para main
   ↓
2. GitHub Actions detecta mudança
   ↓
3. CI roda (testes + qualidade)
   ↓
4. Se CI passar ✅
   ↓
5. CD faz backup do código atual na VPS
   ↓
6. CD envia código novo via rsync
   ↓
7. CD instala dependências do Composer
   ↓
8. CD configura permissões
   ↓
9. ✅ Site atualizado automaticamente!
```

---

## 🚨 Troubleshooting

### Erro: "Permission denied (publickey)"
- Verificar se a chave pública está em `~/.ssh/authorized_keys` na VPS
- Verificar permissões: `chmod 600 ~/.ssh/authorized_keys`

### Erro: "rsync: command not found"
```bash
# Na VPS, instalar rsync
apt update
apt install rsync -y
```

### Erro: "composer: command not found"
```bash
# Na VPS, instalar Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### Ver logs na VPS:
```bash
# Logs do Nginx
tail -f /var/log/nginx/error.log

# Logs do PHP
tail -f /var/log/php8.2-fpm.log

# Logs da aplicação
tail -f /var/www/html/projeto/logs/app.log
```

---

## ✅ Checklist Final

- [ ] Chave SSH configurada
- [ ] 4 Secrets adicionados no GitHub
- [ ] Diretório criado na VPS
- [ ] Arquivo .env configurado na VPS
- [ ] Nginx configurado
- [ ] Primeiro deploy testado
- [ ] Site funcionando

---

## 🎉 Pronto!

Agora toda vez que você fizer `git push origin main`:
- ✅ Testes rodam automaticamente
- ✅ Código é enviado para VPS
- ✅ Site é atualizado

**Sem precisar fazer nada manual!** 🚀
