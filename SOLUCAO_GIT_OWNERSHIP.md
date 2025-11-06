# 🔧 Solução: Git "dubious ownership"

## Problema
```
fatal: detected dubious ownership in repository at '/var/www/aula7'
```

Isso acontece porque o diretório pertence a outro usuário (www-data) e o Git bloqueia por segurança.

## Solução Rápida

Execute no servidor:

```bash
# Adicionar exceção de segurança
git config --global --add safe.directory /var/www/aula7

# Agora pode fazer git pull
git pull
```

## Solução Permanente (Recomendado)

Se quiser evitar esse problema no futuro, ajuste as permissões:

```bash
# Opção 1: Mudar o dono do diretório .git
sudo chown -R root:root /var/www/aula7/.git

# Opção 2: Adicionar ao safe.directory (já feito acima)
git config --global --add safe.directory /var/www/aula7
```

## Depois de resolver, execute:

```bash
# 1. Habilitar site Nginx
sudo ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7

# 2. Remover site padrão
sudo rm -f /etc/nginx/sites-enabled/default

# 3. Testar e recarregar
sudo nginx -t && sudo systemctl reload nginx
```

