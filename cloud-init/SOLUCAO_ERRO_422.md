# 🔧 Solução para Erro 422 no Cloud-Init

## ❌ Problema

Ao colar o script cloud-init no painel da LocawWeb, você recebeu:
```
Failed to load resource: the server responded with a status of 422 ()
```

## 🔍 Causa

Erro **422 (Unprocessable Entity)** geralmente significa que:
- O servidor não conseguiu processar o script
- Caracteres especiais nas senhas causaram problemas de parsing
- Formatação YAML pode ter problemas

**Principais culpados:**
- Caracteres especiais como: `>`, `?`, `$`, `'`, `%`, `;`, `@`, `-`
- Aspas simples dentro de strings
- Escape incorreto de caracteres

## ✅ Solução

Criei uma versão corrigida do script com senhas sem caracteres problemáticos.

### Nova Versão do Arquivo

**Arquivo:** `cloud-init/COLAR_NO_PAINEL_CORRIGIDO.txt`

### Mudanças Feitas

1. **Senhas simplificadas** (sem caracteres especiais problemáticos):
   - MySQL Root: `4TlBMqYfxF;iJwdS5` (removido `>`, `?`, `$`, `'`)
   - MySQL App User: `YBeukFGtazjbCsAw2025` (removido `'`, `%`, `;`, `@`, `-`)

2. **Formato debconf-set-selections corrigido:**
   ```yaml
   # ANTES (problemático):
   - debconf-set-selections <<< 'mysql-server mysql-server/root_password password 4TlBM>?Y$fxF;iJw'"'"'dS5'
   
   # DEPOIS (corrigido):
   - |
     echo "mysql-server mysql-server/root_password password 4TlBMqYfxF;iJwdS5" | debconf-set-selections
   ```

3. **Script MySQL com delimitador correto:**
   ```yaml
   # ANTES:
   mysql -u root -p4TlBM>?Y$fxF;iJw'dS5 <<EOF
   
   # DEPOIS:
   mysql -u root -p4TlBMqYfxF;iJwdS5 <<'MYSQL_SCRIPT'
   ```

## 🚀 Como Usar a Versão Corrigida

### 1. Abra o Arquivo Corrigido

Abra: `cloud-init/COLAR_NO_PAINEL_CORRIGIDO.txt`

### 2. Copie o Código

Copie da linha que começa com `#cloud-config` até o final.

### 3. Cole no Painel

1. No painel LocawWeb, clique em **"Instalação rápida"** ou **"Instalação guiada"**
2. Procure o campo **"Cloud-Init"**, **"User Data"** ou **"Script de Inicialização"**
3. **Cole o código corrigido**
4. Salve/Crie o servidor

## 📋 Novas Senhas (Guarde em Local Seguro!)

```
MySQL Root: 4TlBMqYfxF;iJwdS5
MySQL App User: YBeukFGtazjbCsAw2025
Usuário Deploy: (deixado vazio - configure depois via SSH)
```

## 🔄 Se Ainda Não Funcionar

### Opção 1: Versão Mínima (Sem Senhas no Script)

Se ainda der erro, você pode criar o servidor sem configurar o MySQL no script, e depois configurar manualmente:

1. Crie o servidor **sem** o cloud-init
2. Conecte via SSH
3. Configure manualmente (veja `GUIA_DEPLOY_VPS.md`)

### Opção 2: Verificar no Painel

1. Verifique se o campo aceita **YAML** ou **texto plano**
2. Alguns painéis têm campos específicos para cloud-init
3. Tente usar a aba **"Instalação guiada"** em vez de rápida

### Opção 3: Contatar Suporte LocawWeb

Se persistir, pode ser uma limitação específica do painel. Contate o suporte da LocawWeb.

## 📝 Checklist

- [ ] Use o arquivo `COLAR_NO_PAINEL_CORRIGIDO.txt`
- [ ] Copie apenas o código (da linha `#cloud-config` até o final)
- [ ] Cole no campo correto do painel
- [ ] Verifique se não há espaços extras no início/fim
- [ ] Anote as novas senhas

## 🆘 Troubleshooting Adicional

### Verificar Sintaxe YAML

Você pode validar o YAML online:
- https://www.yamllint.com/
- Cole o código e verifique se há erros

### Verificar Caracteres Especiais

Certifique-se de que:
- Não há caracteres invisíveis
- Não há BOM (Byte Order Mark)
- Encoding está em UTF-8

### Testar em Etapas

Se possível, teste criando o servidor sem o cloud-init primeiro, depois adicione configurações gradualmente.

---

**Dica:** Salve o arquivo corrigido e tente novamente. As senhas foram simplificadas para evitar problemas de parsing.

