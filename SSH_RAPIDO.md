# ⚡ Guia Rápido: Criar Chave SSH em 2 Minutos

## 🚀 Passo a Passo Rápido (Windows)

### 1. Abra o Git Bash
(Instalado com Git - procure no menu iniciar)

### 2. Gere a Chave SSH
```bash
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com"
```

**Quando perguntar:**
- Local: Aperte **ENTER** (usa padrão)
- Senha: Digite uma senha ou deixe vazio

### 3. Copie a Chave Pública
```bash
cat ~/.ssh/id_rsa.pub
```

**Copie TODO o texto que aparecer** (começa com `ssh-rsa`)

### 4. Envie para o Servidor

**Opção A - Automático:**
```bash
ssh-copy-id root@IP_DO_SERVIDOR
```
(Digite a senha do servidor uma vez)

**Opção B - Manual:**
```bash
# 1. Conecte ao servidor
ssh root@IP_DO_SERVIDOR

# 2. No servidor, execute:
mkdir -p ~/.ssh
echo "COLE_SUA_CHAVE_PUBLICA_AQUI" >> ~/.ssh/authorized_keys
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
exit
```

### 5. Teste
```bash
ssh root@IP_DO_SERVIDOR
```

✅ **Pronto!** Agora você conecta sem senha!

---

## 📝 Exemplo Completo

```bash
# 1. Gerar chave
ssh-keygen -t rsa -b 4096 -C "meu-email@gmail.com"
# [Aperte ENTER 3 vezes para usar padrão e sem senha]

# 2. Ver chave pública
cat ~/.ssh/id_rsa.pub

# 3. Copiar para servidor (substitua IP_DO_SERVIDOR)
ssh-copy-id root@IP_DO_SERVIDOR

# 4. Testar
ssh root@IP_DO_SERVIDOR
```

---

## 🆘 Problemas?

**"ssh-copy-id não encontrado":**
- Use o Método B (Manual) acima

**"Permission denied":**
- Verifique se copiou a chave completa
- No servidor: `chmod 700 ~/.ssh && chmod 600 ~/.ssh/authorized_keys`

**Mais detalhes:** Veja `GUIA_SSH.md`

---

**Pronto para deploy!** 🎉

