# 🔐 Guia Completo: Criar Chaves SSH para VPS

Este guia vai te ensinar como criar e configurar chaves SSH para conectar ao seu VPS da LocawWeb de forma segura.

---

## 📋 O que são Chaves SSH?

Chaves SSH são uma forma mais segura de autenticação do que senhas. Você cria um par de chaves:
- **Chave Privada**: Fica no seu computador (NUNCA compartilhe!)
- **Chave Pública**: É enviada para o servidor

---

## 🚀 Método 1: Windows (PowerShell ou Git Bash)

### Passo 1: Verificar se já existe chave SSH

```powershell
# No PowerShell
ls ~/.ssh

# Ou no Git Bash
ls ~/.ssh
```

Se você ver arquivos como `id_rsa` e `id_rsa.pub`, você já tem chaves. Pode pular para o Passo 3.

### Passo 2: Gerar Nova Chave SSH

#### Opção A: Usando Git Bash (Recomendado no Windows)

1. Abra o **Git Bash** (instalado com Git)
2. Execute:

```bash
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com"
```

**Explicação dos parâmetros:**
- `-t rsa`: Tipo de chave (RSA)
- `-b 4096`: Tamanho da chave (4096 bits é mais seguro)
- `-C "email"`: Comentário (geralmente seu email)

**O que vai acontecer:**

```
Generating public/private rsa key pair.
Enter file in which to save the key (/c/Users/SeuUsuario/.ssh/id_rsa):
```

**Aperte ENTER** para usar o local padrão.

```
Enter passphrase (empty for no passphrase):
```

**Escolha uma senha forte** ou deixe vazio (menos seguro, mas mais conveniente).

```
Enter same passphrase again:
```

Confirme a senha.

#### Opção B: Usando PowerShell (Windows 10/11)

```powershell
# Verificar se OpenSSH está instalado
Get-WindowsCapability -Online | Where-Object Name -like 'OpenSSH*'

# Se não estiver instalado, instale:
Add-WindowsCapability -Online -Name OpenSSH.Client~~~~0.0.1.0

# Gerar chave
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com"
```

Siga as mesmas instruções acima.

#### Opção C: Usando PuTTYgen (GUI)

1. Baixe e instale **PuTTY** (inclui PuTTYgen)
2. Abra **PuTTYgen**
3. Selecione **RSA** e **4096 bits**
4. Clique em **Generate**
5. Mova o mouse aleatoriamente para gerar entropia
6. **Salve a chave pública** (botão "Save public key")
7. **Salve a chave privada** (botão "Save private key")
8. **Copie a chave pública** da área de texto

---

### Passo 3: Verificar Chaves Criadas

```bash
# No Git Bash ou PowerShell
ls ~/.ssh
```

Você deve ver:
- `id_rsa` - Chave privada (NUNCA compartilhe!)
- `id_rsa.pub` - Chave pública (esta você envia para o servidor)

---

### Passo 4: Visualizar Chave Pública

```bash
# No Git Bash
cat ~/.ssh/id_rsa.pub

# No PowerShell
Get-Content ~/.ssh/id_rsa.pub

# Ou no Windows (CMD)
type %USERPROFILE%\.ssh\id_rsa.pub
```

A chave pública terá este formato:
```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQC... muito texto aqui ... seu-email@exemplo.com
```

**Copie TODO o conteúdo** (Ctrl+C).

---

### Passo 5: Enviar Chave para o Servidor VPS

#### Método A: Usando ssh-copy-id (Mais Fácil)

**No Git Bash:**

```bash
ssh-copy-id usuario@ip_do_servidor
```

**Exemplo:**
```bash
ssh-copy-id root@192.168.1.100
```

Você será solicitado a digitar a senha do servidor uma última vez.

#### Método B: Manual (Se ssh-copy-id não funcionar)

1. **Conecte ao servidor com senha:**
```bash
ssh usuario@ip_do_servidor
```

2. **No servidor, criar diretório .ssh (se não existir):**
```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
```

3. **Criar/editar arquivo authorized_keys:**
```bash
nano ~/.ssh/authorized_keys
```

4. **Cole sua chave pública** (Ctrl+Shift+V no terminal)
5. **Salvar e sair:** Ctrl+X, depois Y, depois ENTER

6. **Configurar permissões corretas:**
```bash
chmod 600 ~/.ssh/authorized_keys
```

7. **Sair do servidor:**
```bash
exit
```

#### Método C: Usando Painel da LocawWeb

Algumas hospedagens permitem adicionar chaves SSH pelo painel:

1. Acesse o painel da LocawWeb
2. Vá em **SSH Keys** ou **Chaves SSH**
3. Adicione sua chave pública
4. Salve

---

### Passo 6: Testar Conexão

```bash
ssh usuario@ip_do_servidor
```

**Se funcionou:**
- ✅ Você será conectado SEM pedir senha
- ✅ Se configurou passphrase, pode pedir a passphrase da chave

**Se não funcionou:**
- Verifique se copiou a chave pública completa
- Verifique permissões no servidor: `chmod 700 ~/.ssh` e `chmod 600 ~/.ssh/authorized_keys`
- Verifique logs: `tail -f /var/log/auth.log` (no servidor)

---

## 🐧 Método 2: Linux/Mac

O processo é similar:

```bash
# 1. Gerar chave
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com"

# 2. Enviar para servidor
ssh-copy-id usuario@ip_do_servidor

# 3. Testar
ssh usuario@ip_do_servidor
```

---

## 🔧 Configuração Avançada (Opcional)

### Configurar Arquivo SSH Config

Crie/edite `~/.ssh/config`:

```bash
# No Git Bash
nano ~/.ssh/config

# No Windows (PowerShell)
notepad ~/.ssh/config
```

Adicione:

```
Host vps-locawweb
    HostName ip_do_servidor
    User root
    Port 22
    IdentityFile ~/.ssh/id_rsa
```

Agora você pode conectar simplesmente com:
```bash
ssh vps-locawweb
```

---

## 🔒 Segurança

### Boas Práticas:

1. ✅ **Use senha forte na chave privada** (passphrase)
2. ✅ **NUNCA compartilhe a chave privada** (`id_rsa`)
3. ✅ **Use chaves diferentes** para servidores diferentes
4. ✅ **Desabilite login por senha** no servidor (após configurar chaves)
5. ✅ **Mantenha backups** das chaves privadas (em local seguro)

### Desabilitar Login por Senha (Opcional - Mais Seguro)

**No servidor, edite:**
```bash
sudo nano /etc/ssh/sshd_config
```

Altere:
```
PasswordAuthentication no
PubkeyAuthentication yes
```

Reinicie SSH:
```bash
sudo systemctl restart sshd
```

**⚠️ ATENÇÃO:** Certifique-se de que a chave SSH está funcionando ANTES de desabilitar senha!

---

## 🆘 Troubleshooting

### Erro: "Permission denied (publickey)"

**Soluções:**

1. Verificar permissões no servidor:
```bash
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

2. Verificar se a chave está no arquivo correto:
```bash
cat ~/.ssh/authorized_keys
```

3. Verificar logs do servidor:
```bash
sudo tail -f /var/log/auth.log
```

### Erro: "Could not resolve hostname"

Verifique se o IP está correto ou se o domínio está configurado.

### Erro: "Connection refused"

- Verifique se o SSH está rodando no servidor:
```bash
# No servidor
sudo systemctl status ssh
```

- Verifique se a porta 22 está aberta no firewall

### Windows: Chave não encontrada

Certifique-se de que está no diretório correto:
```powershell
# Verificar localização
$env:USERPROFILE\.ssh
```

---

## 📝 Checklist Rápido

- [ ] Chave SSH gerada (`ssh-keygen`)
- [ ] Chave pública copiada (`cat ~/.ssh/id_rsa.pub`)
- [ ] Chave pública adicionada ao servidor (`~/.ssh/authorized_keys`)
- [ ] Permissões configuradas no servidor (`chmod 700 ~/.ssh` e `chmod 600 ~/.ssh/authorized_keys`)
- [ ] Conexão testada (`ssh usuario@ip`)
- [ ] Funcionando sem pedir senha ✅

---

## 🎯 Próximos Passos

Após configurar SSH, você pode:

1. **Fazer deploy do projeto** usando o guia `GUIA_DEPLOY_VPS.md`
2. **Configurar acesso via Git** no servidor
3. **Automatizar deploy** com scripts

---

## 📚 Recursos Adicionais

- [Documentação OpenSSH](https://www.openssh.com/manual.html)
- [Guia GitHub sobre SSH](https://docs.github.com/en/authentication/connecting-to-github-with-ssh)

---

**Última atualização**: 2025-11-05

