# ☁️ Guia Cloud-Init: Configuração Automática do Servidor VPS

Este guia explica como usar scripts **cloud-init** para configurar automaticamente seu servidor VPS durante a instalação.

---

## 📋 O que é Cloud-Init?

**Cloud-Init** é uma ferramenta que permite configurar automaticamente uma instância de servidor cloud/VPS durante o primeiro boot. Com ele, você pode:

- ✅ Instalar pacotes automaticamente
- ✅ Configurar usuários e chaves SSH
- ✅ Configurar serviços (PHP, MySQL, Nginx)
- ✅ Executar scripts personalizados
- ✅ Configurar firewall e segurança

**Resultado:** Servidor totalmente configurado em minutos, sem intervenção manual!

---

## 🚀 Como Usar na LocawWeb

### Passo 1: Preparar o Script Cloud-Init

1. **Escolha o script apropriado:**
   - `cloud-init-ubuntu.yaml` - Para Ubuntu/Debian
   - `cloud-init-centos.yaml` - Para CentOS/RHEL/Rocky Linux

2. **Edite o script antes de usar:**
   - Adicione sua chave SSH pública
   - Defina senhas seguras
   - Configure domínio (se tiver)

### Passo 2: Personalizar o Script

#### a) Adicionar sua Chave SSH

Edite a seção `ssh_authorized_keys`:

```yaml
users:
  - name: deploy
    ssh_authorized_keys:
      - ssh-rsa SUA_CHAVE_PUBLICA_AQUI
```

**Sua chave pública está em:** `MINHA_CHAVE_SSH.txt`

#### b) Definir Senhas

**Para gerar hash de senha (Linux/Mac):**
```bash
openssl passwd -1 "sua_senha_aqui"
```

**Para gerar hash de senha (Windows PowerShell):**
```powershell
$senha = Read-Host -AsSecureString
$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($senha)
$senhaTexto = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)
$salt = [System.Text.Encoding]::UTF8.GetBytes("salt1234567890")
$hash = [System.Convert]::ToBase64String((New-Object System.Security.Cryptography.SHA512Managed).ComputeHash([System.Text.Encoding]::UTF8.GetBytes($senhaTexto + [System.Convert]::ToBase64String($salt))))
```

**Ou use uma ferramenta online:** https://www.mkpasswd.net/

#### c) Configurar Senhas do Banco de Dados

No script, encontre e altere:
```yaml
# MySQL root password
root_password_here

# MySQL app_user password
senha_forte_db_aqui
```

### Passo 3: Usar no Painel LocawWeb

#### Opção A: Upload do Arquivo

1. Acesse o painel da LocawWeb
2. Vá em **Criar VPS** ou **Configurar VPS**
3. Procure por **Cloud-Init** ou **User Data**
4. **Cole o conteúdo** do arquivo YAML ou **faça upload**
5. Crie o servidor

#### Opção B: Durante a Criação

1. No processo de criação do VPS
2. Procure a opção **"Script de inicialização"** ou **"User Data"**
3. Cole o conteúdo do arquivo YAML
4. Finalize a criação

---

## 📝 Exemplo de Uso

### 1. Prepare o Script

```bash
# Copie o arquivo
cp cloud-init/cloud-init-ubuntu.yaml meu-cloud-init.yaml

# Edite com suas informações
nano meu-cloud-init.yaml
```

### 2. Personalize

- ✅ Adicione sua chave SSH
- ✅ Defina senhas seguras
- ✅ Configure domínio (se tiver)

### 3. Use no Painel

- Cole o conteúdo no campo **User Data** ou **Cloud-Init**
- Crie o servidor

---

## 🔧 Personalização Avançada

### Adicionar Mais Usuários

```yaml
users:
  - name: deploy
    # ... configurações ...
  - name: admin
    groups: sudo
    ssh_authorized_keys:
      - ssh-rsa OUTRA_CHAVE_AQUI
```

### Instalar Pacotes Adicionais

```yaml
packages:
  - curl
  - wget
  - git
  - seu-pacote-aqui
```

### Executar Scripts Personalizados

```yaml
runcmd:
  - bash /path/to/seu-script.sh
  - curl -s https://api.exemplo.com/webhook | bash
```

### Configurar Variáveis de Ambiente

```yaml
write_files:
  - path: /etc/environment
    content: |
      APP_ENV=production
      DB_HOST=localhost
    permissions: '0644'
```

---

## ✅ Checklist de Personalização

Antes de usar o cloud-init, verifique:

- [ ] Chave SSH pública adicionada
- [ ] Senhas definidas e hasheadas
- [ ] Senha do MySQL root alterada
- [ ] Senha do usuário app_user alterada
- [ ] Domínio configurado (se tiver)
- [ ] Timezone correto (padrão: America/Sao_Paulo)
- [ ] IP do servidor atualizado no .env.example

---

## 🧪 Testar o Script

### Validação Básica

```bash
# Verificar sintaxe YAML
python3 -c "import yaml; yaml.safe_load(open('cloud-init-ubuntu.yaml'))"
```

### Testar em VM Local (Opcional)

1. Use VirtualBox ou VMware
2. Crie uma VM Ubuntu
3. Configure cloud-init localmente
4. Teste o script antes de usar no VPS

---

## 📊 O que o Script Faz

### Ubuntu/Debian

1. ✅ Atualiza sistema
2. ✅ Instala PHP 8.2 + extensões
3. ✅ Instala Nginx
4. ✅ Instala MySQL
5. ✅ Instala Composer
6. ✅ Cria banco de dados `aula_php_mvc`
7. ✅ Cria usuário do banco `app_user`
8. ✅ Configura Nginx
9. ✅ Configura firewall (UFW)
10. ✅ Instala Fail2Ban
11. ✅ Cria diretórios do projeto
12. ✅ Configura permissões
13. ✅ Otimiza PHP-FPM

### CentOS/RHEL

1. ✅ Atualiza sistema
2. ✅ Adiciona repositórios EPEL e Remi
3. ✅ Instala PHP 8.2 + extensões
4. ✅ Instala Nginx
5. ✅ Instala MariaDB
6. ✅ Instala Composer
7. ✅ Configura banco de dados
8. ✅ Configura Nginx
9. ✅ Configura firewall (firewalld)
10. ✅ Instala Fail2Ban

---

## 🔍 Verificar Após Instalação

Após o servidor inicializar, conecte via SSH:

```bash
ssh deploy@IP_DO_SERVIDOR
```

Execute:

```bash
# Verificar serviços
systemctl status php8.2-fpm
systemctl status nginx
systemctl status mysql

# Verificar versões
php -v
mysql --version
nginx -v
composer --version

# Verificar diretórios
ls -la /var/www/aula7

# Verificar firewall
ufw status  # Ubuntu
# ou
firewall-cmd --list-all  # CentOS
```

---

## 🆘 Troubleshooting

### Script não executou

**Verificar logs:**
```bash
# No servidor
sudo cat /var/log/cloud-init-output.log
sudo cat /var/log/cloud-init.log
```

### Serviços não iniciaram

```bash
# Verificar status
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql

# Ver logs
journalctl -u nginx
journalctl -u php8.2-fpm
journalctl -u mysql
```

### Permissões incorretas

```bash
# Corrigir permissões
sudo chown -R deploy:www-data /var/www/aula7
sudo chmod -R 755 /var/www/aula7
sudo chmod -R 775 /var/www/aula7/logs
```

### Banco de dados não criado

```bash
# Conectar ao MySQL
sudo mysql -u root -p

# Criar manualmente se necessário
CREATE DATABASE aula_php_mvc;
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'senha';
GRANT ALL PRIVILEGES ON aula_php_mvc.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## 📚 Recursos Adicionais

### Documentação Oficial

- [Cloud-Init Documentation](https://cloudinit.readthedocs.io/)
- [Cloud-Init Examples](https://cloudinit.readthedocs.io/en/latest/topics/examples.html)

### Ferramentas Úteis

- **Validador YAML Online**: https://www.yamllint.com/
- **Gerador de Hash de Senha**: https://www.mkpasswd.net/

---

## 🎯 Próximos Passos

Após o cloud-init configurar o servidor:

1. ✅ Verificar se tudo está funcionando
2. ✅ Conectar via SSH
3. ✅ Fazer deploy do código (veja `GUIA_DEPLOY_VPS.md`)
4. ✅ Configurar arquivo `.env`
5. ✅ Importar schema do banco
6. ✅ Testar aplicação

---

## ⚠️ Importante

### Segurança

- 🔒 **Altere todas as senhas padrão** após a primeira conexão
- 🔒 **Desabilite login root** (se possível)
- 🔒 **Configure SSL/HTTPS** (Let's Encrypt)
- 🔒 **Mantenha sistema atualizado** (`apt update && apt upgrade`)

### Backup

- 💾 Faça backup do arquivo cloud-init personalizado
- 💾 Guarde as senhas em local seguro
- 💾 Documente as configurações

---

**Última atualização**: 2025-11-05

