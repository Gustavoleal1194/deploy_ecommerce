# ✅ SCRIPT PRONTO PARA COLAR NO PAINEL!

## 🎉 O que foi feito automaticamente:

1. ✅ **Senhas geradas** automaticamente
2. ✅ **Script atualizado** com todas as senhas
3. ✅ **Chave SSH** já incluída
4. ✅ **Tudo configurado** e pronto para usar!

---

## 📋 Senhas Geradas (GUARDE EM LOCAL SEGURO!)

```
1. Usuário deploy: QBZAG1rPKCzt4XJe
2. MySQL root: 4TlBM>?Y$fxF;iJw'dS5
3. MySQL app_user: YBeuk'FG%;tazjbCs@w-
```

**Arquivo salvo em:** `cloud-init/senhas-geradas.txt`

---

## 🚀 PRÓXIMOS PASSOS

### 1. Abrir o Arquivo

Abra: `cloud-init/COLAR_NO_PAINEL.txt`

### 2. Copiar o Código

1. **Copie TODO o conteúdo** a partir da linha 10 (depois do cabeçalho)
2. Vá até o final do arquivo
3. **Selecione tudo** (Ctrl+A) e **copie** (Ctrl+C)

**OU** copie apenas a partir da linha que começa com `#cloud-config` até o final.

### 3. Colar no Painel LocawWeb

1. Acesse o painel da LocawWeb
2. Vá em **Criar VPS** ou **Configurar VPS**
3. Encontre o campo **"Cloud-Init"** ou **"User Data"** ou **"Script de Inicialização"**
4. **Cole o código** copiado (Ctrl+V)
5. Finalize a criação do servidor

---

## 📁 Arquivo para Copiar

**Localização:** `cloud-init/COLAR_NO_PAINEL.txt`

**Linhas para copiar:** Da linha 10 até o final (ou da linha com `#cloud-config` até o final)

---

## ✅ O que o Script Faz

Quando o servidor for criado, ele automaticamente:

- ✅ Instala PHP 8.2 + extensões
- ✅ Instala Nginx
- ✅ Instala MySQL
- ✅ Cria banco de dados `aula_php_mvc`
- ✅ Cria usuário `app_user` do banco
- ✅ Instala Composer
- ✅ Configura Nginx
- ✅ Configura firewall
- ✅ Instala Fail2Ban
- ✅ Cria diretório `/var/www/aula7`
- ✅ Configura permissões
- ✅ Cria usuário `deploy` com sua chave SSH

**Tempo estimado:** 5-10 minutos

---

## 🔐 Após Criar o Servidor

### 1. Conectar via SSH

```bash
ssh deploy@IP_DO_SERVIDOR
```

**Senha:** `QBZAG1rPKCzt4XJe` (ou configure via SSH key)

### 2. Definir Senha do Deploy (Recomendado)

```bash
passwd
```

### 3. Verificar Instalação

```bash
# Ver versões
php -v
mysql --version
nginx -v
composer --version

# Verificar serviços
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql
```

### 4. Ver Informações do Servidor

```bash
/home/deploy/setup-completo.sh
```

---

## 📝 Credenciais do Banco de Dados

Após o servidor criar, use estas credenciais:

```
Host: localhost
Database: aula_php_mvc
User: app_user
Password: YBeuk'FG%;tazjbCs@w-
```

**MySQL Root:**
```
User: root
Password: 4TlBM>?Y$fxF;iJw'dS5
```

---

## ⚠️ IMPORTANTE

1. **GUARDE as senhas** em local seguro
2. **Altere as senhas** após o primeiro acesso (recomendado)
3. **Não compartilhe** as senhas
4. **Faça backup** do arquivo `senhas-geradas.txt`

---

## 🆘 Problemas?

### Servidor não criou?

- Verifique os logs no painel da LocawWeb
- Verifique se o código foi colado corretamente
- Verifique se não há erros de sintaxe

### Não consegue conectar via SSH?

- Verifique se a chave SSH está correta
- Tente conectar com senha: `ssh deploy@IP_DO_SERVIDOR`
- Use a senha: `QBZAG1rPKCzt4XJe`

### Serviços não iniciaram?

```bash
# Verificar status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql

# Ver logs
sudo journalctl -u nginx
sudo journalctl -u php8.2-fpm
sudo journalctl -u mysql
```

---

## 🎯 Próximos Passos Após Servidor Criado

1. ✅ Conectar via SSH
2. ✅ Fazer deploy do código (veja `GUIA_DEPLOY_VPS.md`)
3. ✅ Configurar arquivo `.env`
4. ✅ Importar schema do banco
5. ✅ Testar aplicação

---

**Tudo pronto!** 🚀 Agora é só copiar e colar no painel!

