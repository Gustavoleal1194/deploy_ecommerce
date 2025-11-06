# 🔐 Como Visualizar sua Chave Privada SSH

## ⚠️ IMPORTANTE: SEGURANÇA

A chave privada SSH é **CRÍTICA** para sua segurança!
- **NUNCA** compartilhe sua chave privada
- **NUNCA** envie por email ou mensagem
- **NUNCA** faça commit da chave privada no Git
- Guarde apenas em local seguro

---

## 📍 Localização da Chave Privada

Sua chave privada está em:
```
C:\Users\Gustavo Leal\.ssh\id_rsa
```

---

## 🔍 Métodos para Visualizar

### **Método 1: PowerShell (Recomendado no Windows)**

```powershell
# Visualizar a chave privada completa
Get-Content "$env:USERPROFILE\.ssh\id_rsa"

# Ou usando cat (se disponível)
cat "$env:USERPROFILE\.ssh\id_rsa"
```

### **Método 2: Git Bash**

```bash
# Visualizar a chave privada
cat ~/.ssh/id_rsa

# Ou com caminho completo
cat /c/Users/"Gustavo Leal"/.ssh/id_rsa
```

### **Método 3: Bloco de Notas (Windows)**

1. Abra o Bloco de Notas
2. Vá em **Arquivo > Abrir**
3. Na barra de endereço, cole: `C:\Users\Gustavo Leal\.ssh`
4. Selecione **"Todos os Arquivos"** no filtro
5. Abra o arquivo `id_rsa` (sem extensão)
6. ⚠️ **CUIDADO**: Não salve alterações acidentais!

### **Método 4: Visual Studio Code**

```powershell
# Abrir no VS Code
code "$env:USERPROFILE\.ssh\id_rsa"
```

---

## 📋 Formato da Chave Privada

Sua chave privada deve ter este formato:

```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAABlwAAAAdzc2gtcn
NhAAAAAwEAAQAAAYEAwIrnG+fv5RwYG8CDy1M3UGWIHnuS4f+OmCFJIxwyc1Zt0dM/Qkxa
...
(muitas linhas de caracteres codificados)
...
-----END OPENSSH PRIVATE KEY-----
```

Ou se for RSA tradicional:

```
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAwIrnG+fv5RwYG8CDy1M3UGWIHnuS4f+OmCFJIxwyc1Zt0dM/
...
-----END RSA PRIVATE KEY-----
```

---

## 🔑 Informações da Chave

Para ver informações sobre sua chave (sem revelar o conteúdo):

```powershell
# Ver fingerprint da chave pública
ssh-keygen -lf "$env:USERPROFILE\.ssh\id_rsa.pub"

# Ver tipo e tamanho da chave
ssh-keygen -l -f "$env:USERPROFILE\.ssh\id_rsa.pub"
```

---

## 💾 Copiar Chave Privada (USE COM CUIDADO!)

### Para usar em outro computador:

1. **Copie o arquivo completo** `id_rsa` (não apenas o conteúdo)
2. Cole no diretório `.ssh` do outro computador
3. Configure permissões corretas (Linux/Mac):
   ```bash
   chmod 600 ~/.ssh/id_rsa
   ```

### Exportar para backup seguro:

```powershell
# Criar backup criptografado (ZIP com senha)
Compress-Archive -Path "$env:USERPROFILE\.ssh\id_rsa" -DestinationPath "backup-chave-privada.zip"
```

---

## 🚨 Quando você precisa da chave privada?

- ✅ **Acessar servidor VPS** via SSH sem senha
- ✅ **Configurar Git** para push/pull com SSH
- ✅ **Backup** em local seguro
- ✅ **Migrar** para outro computador

❌ **NÃO precisa** para:
- Adicionar chave no GitHub/GitLab (só precisa da chave pública)
- Configurar servidor (só precisa da chave pública)
- Compartilhar com outros desenvolvedores

---

## 🔄 Gerar Nova Chave (se necessário)

Se você perdeu a chave privada ou precisa de uma nova:

```powershell
# Gerar novo par de chaves
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com" -f "$env:USERPROFILE\.ssh\id_rsa_nova"

# Isso criará:
# - id_rsa_nova       (chave privada)
# - id_rsa_nova.pub   (chave pública)
```

---

## ✅ Verificar se a chave está funcionando

```powershell
# Testar conexão SSH com a chave
ssh -i "$env:USERPROFILE\.ssh\id_rsa" root@191.252.93.136

# Ou se a chave está no local padrão
ssh root@191.252.93.136
```

---

## 📝 Checklist de Segurança

- [ ] Chave privada está em local seguro
- [ ] Arquivo `.ssh/id_rsa` não está no Git (verifique `.gitignore`)
- [ ] Permissões do arquivo estão corretas (600 no Linux/Mac)
- [ ] Você tem backup seguro da chave privada
- [ ] Não compartilhou a chave privada com ninguém

---

## 🔗 Arquivos Relacionados

- **Chave Pública**: `C:\Users\Gustavo Leal\.ssh\id_rsa.pub`
- **Documentação SSH**: Veja `MINHA_CHAVE_SSH.txt`
- **Configuração VPS**: Veja `CONFIGURACAO_VPS.md`

---

**Última atualização**: 06/11/2025

