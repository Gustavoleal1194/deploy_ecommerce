# 🔄 Como Atualizar o Servidor

## Após fazer mudanças no código local

1. **Commit e Push no seu computador:**
   ```bash
   git add .
   git commit -m "Descrição das mudanças"
   git push
   ```

2. **No servidor, fazer Pull:**
   ```bash
   cd /var/www/aula7
   git pull
   ```

## Se houver conflitos

```bash
# Descartar mudanças locais e atualizar
git reset --hard HEAD
git pull
```

## Após atualizar, verificar

```bash
# Verificar se está atualizado
git log --oneline -5

# Recarregar Nginx se necessário
sudo systemctl reload nginx
```

