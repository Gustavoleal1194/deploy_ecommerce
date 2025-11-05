# 🔧 Solução: Erro ao Instalar Servidor

## ❌ Erro Recebido

"Erro ao tentar realizar a instalação, tente novamente."

## 🔍 Possíveis Causas

1. **Chave SSH com quebras de linha ou espaços**
2. **Chave SSH incompleta ou mal formatada**
3. **Problema temporário do servidor LocawWeb**

## ✅ Solução Passo a Passo

### 1. Limpar e Recolar a Chave SSH

A chave SSH deve ser **UMA LINHA SÓ**, sem quebras de linha.

**Sua chave SSH correta (copie TUDO em uma linha):**

```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQDAiucb5+/lHBgbwIPLUzdQZYgee5Lh/46YIUkjHDJzVm3R0z9CTFoLaSHxATSvve0/0GMu+b7E0Jyqd0/u4TUDq1fm2/Z+b9hnwt1kNOBEL9ni6KaPbW3g/qnqKLkRWDU6hBryauXDBVOXJ2Fp4er+Q6/GMejLBoNF3vBkDghh4liw9m/9eGXxal3+5DKU57A3bZH9k56jovYwVemZ3aEU3pScFgfDI3NU1IqC86/sAVDh10Z6V8jykIPc/qy2iyvREqp+f0zGNhobWHObYJTnXeyHU5TDcEh0iQT6oQVmuMgu9DGCNHSqnI7fYJrPJTNiEFaAybNTyTRKklRBzahJkE/dt0vc2Bgp23YgqfdfFk4Wsc6ychT8RFNkn3iZSGkh1WuYiVMtbBVFMXaCyvL1cOvoQ9+I442Y/LXDsJu1ivfZOr7eU/aHQqgvS7zx0+E1FKn7IN6uXwK4ggQ8p5Z5Zyd8qqRhqXSd+OUAG2m/uw9l2j2V3ao08mWn+wrnfCmQeYmePjMZ0Jrq6YRcjjBkevKp4VLlQSoeHqJ5lv7dyh0fYzu42AOJZQRhjC4VbWrg43FLxxPqmIBcmDSnpKfC0dgdVx/jzIn0I+hFRBOPcOsrOu9kcEcDYesRf/hNQALnUrIMNLgYkRVC1vslujbGg2aiEr//h8bbNd96oa0F5Q== aula7-vps-20251105
```

### 2. Passos para Corrigir

1. **Limpe o campo** da chave SSH (selecione tudo e delete)
2. **Copie a chave acima** (uma linha completa, sem quebras)
3. **Cole no campo** usando Ctrl+V
4. **Verifique** que está tudo em uma linha só
5. **Tente instalar novamente**

### 3. Alternativa: Usar Senha

Se continuar com erro, tente usar senha:

1. **Selecione "Senha"** em vez de "Chave pública"
2. **Defina uma senha forte** (mínimo 12 caracteres)
3. **Anote a senha** em local seguro
4. **Tente instalar**

Depois você pode adicionar a chave SSH manualmente via SSH.

### 4. Verificar no Painel

- Verifique se o nome do servidor está correto
- Verifique se a versão do Ubuntu está selecionada (20.04 LTS)
- Certifique-se de que não há espaços extras no nome

## 🔄 Tentar Novamente

1. **Recarregue a página** (F5)
2. **Limpe o cache** (Ctrl+Shift+R)
3. **Cole a chave novamente** (garantindo que está em uma linha)
4. **Clique em "Instalar servidor"**

## 🆘 Se Ainda Não Funcionar

### Opção 1: Contatar Suporte LocawWeb

O erro pode ser do lado deles. Contate o suporte.

### Opção 2: Criar Servidor Sem Cloud-Init

1. Crie o servidor **sem** o script cloud-init
2. Conecte via SSH após criar
3. Configure manualmente (veja `GUIA_DEPLOY_VPS.md`)

### Opção 3: Verificar Logs

Se houver console do navegador (F12), verifique se há mais detalhes do erro.

## 📝 Checklist

- [ ] Chave SSH em uma linha só (sem quebras)
- [ ] Chave SSH completa (começa com `ssh-rsa` e termina com `aula7-vps-20251105`)
- [ ] Sem espaços extras no início/fim
- [ ] Nome do servidor preenchido
- [ ] Ubuntu 20.04 LTS selecionado
- [ ] Toggle "Quero incluir um script" desligado (se não for usar)

---

**Dica:** Às vezes o erro é temporário. Tente novamente após alguns minutos.

