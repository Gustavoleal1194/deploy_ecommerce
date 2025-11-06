# 🎯 Guia Rápido: CI/CD para VPS Locaweb

**Referência rápida para configurar deploy automático**

---

## ✅ Checklist de Configuração

### 1️⃣ GitHub Secrets (4 obrigatórios)

Acesse: `Settings` → `Secrets and variables` → `Actions` → `New repository secret`

| Nome | Valor | Exemplo |
|------|-------|---------|
| `SSH_PRIVATE_KEY` | Conteúdo da chave privada SSH | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `VPS_HOST` | IP ou domínio da VPS | `123.45.67.89` |
| `VPS_USER` | Usuário SSH | `root` |
| `VPS_PATH` | Caminho completo no servidor | `/var/www/html/projeto` |

### 2️⃣ Comandos na VPS

```bash
# 1. Conectar na VPS
ssh root@SEU_IP

# 2. Criar diretórios
mkdir -p /var/www/html/projeto/logs
chown -R www-data:www-data /var/www/html/projeto
chmod -R 755 /var/www/html/projeto
chmod 777 /var/www/html/projeto/logs

# 3. Criar .env
nano /var/www/html/projeto/.env
# (adicionar configurações do banco)

# 4. Adicionar chave SSH pública
nano ~/.ssh/authorized_keys
# (colar sua chave pública)
chmod 600 ~/.ssh/authorized_keys

# 5. Instalar rsync e Composer
apt install rsync -y
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### 3️⃣ Testar Deploy

```powershell
# No seu computador
git add .
git commit -m "test: configurar CI/CD"
git push origin main
```

**Ver resultado:** https://github.com/Gustavoleal1194/deploy_ecommerce/actions

---

## 🔑 Gerar Chave SSH (se não tem)

```powershell
# PowerShell
ssh-keygen -t ed25519 -C "github-actions"

# Ver chave PÚBLICA (adicionar na VPS)
cat ~/.ssh/id_ed25519.pub

# Ver chave PRIVADA (adicionar no GitHub Secret)
cat ~/.ssh/id_ed25519
```

---

## 🌐 Configuração Nginx (básica)

```nginx
server {
    listen 80;
    server_name seu_dominio.com.br;
    root /var/www/html/projeto;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
}
```

Ativar:
```bash
ln -s /etc/nginx/sites-available/projeto /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

---

## 🚀 Workflows Configurados

### ✅ CI - Testes e Qualidade (`.github/workflows/ci.yml`)
- Roda em: `push` e `pull_request` para `main` e `develop`
- Executa: testes, PHPStan, PHPCS, análise de segurança
- Build Docker

### ✅ CD - Deploy VPS (`.github/workflows/cd-vps.yml`)
- Roda em: `push` para `main` (depois do CI passar)
- Executa: backup, deploy via rsync, instala dependências
- Configura permissões

---

## 🔄 Fluxo Automático

```
Você → git push origin main
         ↓
GitHub Actions → CI (testes)
         ↓
      ✅ Passou?
         ↓
GitHub Actions → CD (deploy)
         ↓
    VPS Locaweb → Site atualizado! 🚀
```

---

## 🚨 Troubleshooting Rápido

### Erro SSH:
```bash
# Na VPS, verificar:
cat ~/.ssh/authorized_keys  # Tem sua chave?
chmod 600 ~/.ssh/authorized_keys
```

### Erro rsync:
```bash
apt install rsync -y
```

### Erro Composer:
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### Ver logs:
```bash
# Na VPS
tail -f /var/www/html/projeto/logs/app.log
tail -f /var/log/nginx/error.log
```

---

## 📖 Documentação Completa

- **[SETUP.md](.github/workflows/SETUP.md)** - Guia detalhado passo-a-passo
- **[DEVOPS.md](DEVOPS.md)** - Práticas DevOps do projeto

---

**🎉 Pronto! Agora é só fazer `git push` e deixar o resto com o GitHub Actions!**
