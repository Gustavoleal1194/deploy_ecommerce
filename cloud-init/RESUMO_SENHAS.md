# 🔐 Resumo: Onde Colocar as Senhas no Script

## 📋 3 Senhas Necessárias

Você precisa definir **3 senhas** no arquivo `COLAR_NO_PAINEL.txt`:

1. ✅ **MySQL Root** - Administrador do banco de dados
2. ✅ **MySQL App User** - Usuário da aplicação
3. ⚠️ **Usuário Deploy** - Pode deixar vazio (opcional)

---

## 🚀 Passo a Passo Rápido

### 1. Gerar Senhas

**Opção A: Usar o script PowerShell**
```powershell
cd cloud-init
powershell -ExecutionPolicy Bypass -File gerar-senhas.ps1
```

**Opção B: Gerar manualmente**
- Use: https://www.random.org/passwords/
- Ou crie senhas fortes você mesmo
- Mínimo 16-20 caracteres

**Opção C: Definir senhas simples (para teste)**
```
MySQL Root: Root123!@#Senha
MySQL App: AppUser123!@#Senha
```

### 2. Abrir o Arquivo

Abra: `cloud-init/COLAR_NO_PAINEL.txt`

### 3. Substituir as Senhas

Encontre e substitua estas linhas:

---

## 📍 LOCAL 1: Senha MySQL Root (Linha ~60)

**PROCURE:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password root_password_here'
```

**SUBSTITUA por:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password SUA_SENHA_ROOT_AQUI'
```

**Exemplo:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password Root123!@#Senha'
```

---

## 📍 LOCAL 2: Senha MySQL Root - Segunda vez (Linha ~61)

**PROCURE:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root_password_here'
```

**SUBSTITUA por:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password SUA_SENHA_ROOT_AQUI'
```

**Exemplo:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password Root123!@#Senha'
```

---

## 📍 LOCAL 3: Senha MySQL Root - Terceira vez (Linha ~80)

**PROCURE:**
```yaml
- mysql -u root -proot_password_here <<EOF
```

**SUBSTITUA por:**
```yaml
- mysql -u root -pSUA_SENHA_ROOT_AQUI <<EOF
```

**Exemplo:**
```yaml
- mysql -u root -pRoot123!@#Senha <<EOF
```

---

## 📍 LOCAL 4: Senha MySQL App User (Linha ~82)

**PROCURE:**
```yaml
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'senha_forte_db_aqui';
```

**SUBSTITUA por:**
```yaml
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'SUA_SENHA_APP_AQUI';
```

**Exemplo:**
```yaml
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'AppUser123!@#Senha';
```

---

## 📍 LOCAL 5: Senha MySQL App User - No .env (Linha ~166)

**PROCURE:**
```yaml
      DB_PASS=senha_forte_db_aqui
```

**SUBSTITUA por:**
```yaml
      DB_PASS=SUA_SENHA_APP_AQUI
```

**Exemplo:**
```yaml
      DB_PASS=AppUser123!@#Senha
```

---

## ⚠️ LOCAL 6: Senha Usuário Deploy (Linha ~22) - OPCIONAL

**PROCURE:**
```yaml
    passwd: $6$rounds=4096$salt1234567890$hashed_password_here
```

**OPÇÃO 1: Deixar vazio (recomendado)**
```yaml
    passwd: ""
```

**OPÇÃO 2: Definir depois**
- Deixe como está
- Após criar o servidor, conecte via SSH e execute: `passwd`

---

## ✅ Checklist Final

Antes de colar no painel, verifique:

- [ ] **LOCAL 1** - Senha MySQL root substituída (linha ~60)
- [ ] **LOCAL 2** - Senha MySQL root substituída (linha ~61)
- [ ] **LOCAL 3** - Senha MySQL root substituída (linha ~80)
- [ ] **LOCAL 4** - Senha MySQL app_user substituída (linha ~82)
- [ ] **LOCAL 5** - Senha MySQL app_user substituída (linha ~166)
- [ ] **LOCAL 6** - Senha deploy (pode deixar vazio)
- [ ] Todas as senhas anotadas em local seguro

---

## 📝 Exemplo Completo

Se você definiu:
- MySQL Root: `Root123!@#Senha`
- MySQL App User: `AppUser123!@#Senha`

**Substitua assim:**

**Linha 60:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password password Root123!@#Senha'
```

**Linha 61:**
```yaml
- debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password Root123!@#Senha'
```

**Linha 80:**
```yaml
- mysql -u root -pRoot123!@#Senha <<EOF
```

**Linha 82:**
```yaml
    CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'AppUser123!@#Senha';
```

**Linha 166:**
```yaml
      DB_PASS=AppUser123!@#Senha
```

---

## 🎯 Dica: Use Busca e Substituição

No seu editor de texto:

1. **Ctrl+H** (Buscar e Substituir)
2. Buscar: `root_password_here`
3. Substituir por: `SuaSenhaRootAqui`
4. Substituir **TODAS**

Repita para:
- `senha_forte_db_aqui` → `SuaSenhaAppAqui`

---

## 🔒 Depois de Criar o Servidor

1. **Anote as senhas** em local seguro
2. **Conecte ao servidor:**
   ```bash
   ssh deploy@IP_DO_SERVIDOR
   ```
3. **Defina senha do deploy (se deixou vazio):**
   ```bash
   passwd
   ```

---

## 🆘 Problemas?

**"Não encontrei a linha"**
- Use Ctrl+F para buscar: `root_password_here` ou `senha_forte_db_aqui`

**"Esqueci a senha"**
- Veja o arquivo `senhas-geradas.txt` (se usou o script)
- Ou redefina via SSH (se tiver acesso root)

---

**Pronto!** Agora você pode colar o script no painel da LocawWeb! 🚀

