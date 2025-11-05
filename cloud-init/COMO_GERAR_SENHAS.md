# 🔐 Como Gerar Senhas para o Cloud-Init

Este guia mostra como gerar e configurar todas as senhas necessárias no script cloud-init.

---

## 📋 Senhas Necessárias

Você precisa definir **3 senhas** no script:

1. **Senha do usuário `deploy`** (usuário SSH do servidor)
2. **Senha do MySQL root** (administrador do banco)
3. **Senha do MySQL `app_user`** (usuário da aplicação)

---

## 🔧 Método 1: Gerar Senhas Automaticamente (Recomendado)

### Opção A: Usando PowerShell (Windows)

Abra o PowerShell e execute:

```powershell
# Gerar senha aleatória para deploy
$senhaDeploy = -join ((65..90) + (97..122) + (48..57) | Get-Random -Count 16 | ForEach-Object {[char]$_})
Write-Host "Senha do usuário deploy: $senhaDeploy"

# Gerar senha aleatória para MySQL root
$senhaRoot = -join ((65..90) + (97..122) + (48..57) + (33..47) | Get-Random -Count 20 | ForEach-Object {[char]$_})
Write-Host "Senha MySQL root: $senhaRoot"

# Gerar senha aleatória para app_user
$senhaApp = -join ((65..90) + (97..122) + (48..57) + (33..47) | Get-Random -Count 20 | ForEach-Object {[char]$_})
Write-Host "Senha MySQL app_user: $senhaApp"
```

**Anote as 3 senhas geradas!**

### Opção B: Usando Site Online

1. Acesse: https://www.random.org/passwords/
2. Configure:
   - Quantidade: 3 senhas
   - Comprimento: 20 caracteres
   - Incluir símbolos: Sim
3. Gere e **anote as senhas**

### Opção C: Definir Manualmente

Escolha senhas fortes que você mesmo cria:
- ✅ Mínimo 16 caracteres
- ✅ Misture letras maiúsculas, minúsculas, números e símbolos
- ✅ Não use palavras do dicionário
- ✅ Exemplo: `MinhaSenh@2025!Segura`

**Exemplo de senhas:**
```
Deploy:  Deploy@2025!Seguro
MySQL Root:  Root@MySQL2025!Admin
App User:  AppUser@2025!Aula7
```

---

## 🔑 Como Gerar Hash da Senha do Usuário Deploy

O script precisa da senha do usuário `deploy` em formato hash. Veja como gerar:

### Windows (PowerShell)

```powershell
# Método 1: Usando OpenSSL (se tiver instalado)
# Baixe OpenSSL: https://slproweb.com/products/Win32OpenSSL.html
openssl passwd -1 "sua_senha_aqui"

# Método 2: Deixar vazio (menos seguro, mas funciona)
# O cloud-init vai pedir senha no primeiro login
```

**Se não tiver OpenSSL, você pode:**
1. Deixar o hash como está no script
2. Definir senha depois via SSH:
   ```bash
   ssh deploy@IP_DO_SERVIDOR
   passwd
   ```

### Linux/Mac

```bash
openssl passwd -1 "sua_senha_aqui"
```

**Ou deixar vazio** e configurar depois.

---

## 📝 Como Configurar no Script

### 1. Abra o arquivo `COLAR_NO_PAINEL.txt`

### 2. Encontre e substitua estas linhas:

#### Linha ~20: Senha do usuário deploy (hash)
```yaml
# ANTES:
passwd: $6$rounds=4096$salt1234567890$hashed_password_here

# DEPOIS (se gerou hash):
passwd: $1$hash_gerado_aqui

# OU (se vai deixar vazio):
passwd: ""  # Deixar vazio e configurar depois
```

#### Linha ~60: Senha do MySQL root
```yaml
# ANTES:
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password root_password_here'

# DEPOIS:
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password MinhaSenhaRoot@2025!'
```

#### Linha ~80: Senha do MySQL root (novamente)
```yaml
# ANTES:
- mysql -u root -proot_password_here <<EOF

# DEPOIS:
- mysql -u root -pMinhaSenhaRoot@2025! <<EOF
```

#### Linha ~82: Senha do MySQL app_user
```yaml
# ANTES:
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'senha_forte_db_aqui';

# DEPOIS:
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'AppUser@2025!Aula7';
```

#### Linha ~166: Senha do MySQL app_user (no .env.example)
```yaml
# ANTES:
      DB_PASS=senha_forte_db_aqui

# DEPOIS:
      DB_PASS=AppUser@2025!Aula7
```

---

## 🎯 Exemplo Completo Prático

### Passo 1: Definir suas senhas

Vou usar este exemplo (você deve criar suas próprias):

```
1. Senha deploy: Deploy@2025!Seguro
2. Senha MySQL root: Root@MySQL2025!Admin  
3. Senha MySQL app_user: AppUser@2025!Aula7
```

### Passo 2: Editar o script

No arquivo `COLAR_NO_PAINEL.txt`, faça estas substituições:

**Linha 20:**
```yaml
passwd: ""  # Deixar vazio, configurar depois
```

**Linha 60:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password Root@MySQL2025!Admin'
```

**Linha 61:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password Root@MySQL2025!Admin'
```

**Linha 80:**
```yaml
- mysql -u root -pRoot@MySQL2025!Admin <<EOF
```

**Linha 82:**
```yaml
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'AppUser@2025!Aula7';
```

**Linha 166:**
```yaml
      DB_PASS=AppUser@2025!Aula7
```

---

## ✅ Checklist Antes de Colar

Antes de colar o script no painel, verifique:

- [ ] **Senha MySQL root** definida (2 lugares - linhas 60 e 80)
- [ ] **Senha MySQL app_user** definida (2 lugares - linhas 82 e 166)
- [ ] **Senha deploy** (pode deixar vazio e configurar depois)
- [ ] **Chave SSH** já está no script ✅
- [ ] **Todas as senhas anotadas** em local seguro

---

## 🔒 Segurança

### Boas Práticas:

1. ✅ Use senhas diferentes para cada propósito
2. ✅ Mínimo 16 caracteres
3. ✅ Misture maiúsculas, minúsculas, números e símbolos
4. ✅ Não use informações pessoais
5. ✅ Anote em local seguro (gerenciador de senhas)

### Após o Servidor Criar:

1. **Altere a senha do deploy:**
   ```bash
   ssh deploy@IP_DO_SERVIDOR
   passwd
   ```

2. **Altere a senha do MySQL root:**
   ```bash
   sudo mysql -u root -p
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'NovaSenhaSegura';
   ```

3. **Guarde as senhas** em local seguro!

---

## 🆘 Dúvidas Comuns

### "Não quero gerar hash, pode deixar vazio?"

**Sim!** Deixe assim:
```yaml
passwd: ""
```

Você pode definir a senha depois:
```bash
ssh deploy@IP_DO_SERVIDOR
passwd
```

### "Posso usar a mesma senha para tudo?"

**Não recomendado**, mas tecnicamente possível. Use senhas diferentes para mais segurança.

### "Esqueci a senha, como recupero?"

**MySQL root:**
```bash
sudo mysql -u root
# Se não pedir senha, você pode redefinir
```

**Usuário deploy:**
```bash
# Se tiver acesso root
sudo passwd deploy
```

---

## 📝 Template de Anotação

Anote suas senhas aqui (em local seguro):

```
=== SENHAS DO SERVIDOR VPS ===

Data: ___________

Servidor: ___________

1. Usuário deploy:
   Senha: ___________

2. MySQL root:
   Senha: ___________

3. MySQL app_user:
   Senha: ___________

IP do servidor: ___________
```

---

**Dica:** Use um gerenciador de senhas como:
- LastPass
- 1Password  
- Bitwarden
- Keepass

