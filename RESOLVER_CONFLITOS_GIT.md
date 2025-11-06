# 🔧 Resolver Conflitos no Git - Servidor

## Problema
```
error: Your local changes to the following files would be overwritten by merge
```

## Solução Rápida (Recomendada)

Se as mudanças locais não são importantes, descarte e atualize:

```bash
# 1. Descartar mudanças locais
git reset --hard HEAD

# 2. Atualizar do repositório
git pull
```

## Solução Alternativa (Se quiser salvar as mudanças)

```bash
# 1. Salvar mudanças locais
git stash

# 2. Atualizar do repositório
git pull

# 3. Verificar se precisa aplicar as mudanças salvas
git stash list

# 4. Se necessário, aplicar de volta (opcional)
# git stash pop
```

## Após resolver, corrigir Nginx:

```bash
# Habilitar site
sudo ln -sf /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/aula7

# Remover site padrão
sudo rm -f /etc/nginx/sites-enabled/default

# Testar e recarregar
sudo nginx -t && sudo systemctl reload nginx
```

