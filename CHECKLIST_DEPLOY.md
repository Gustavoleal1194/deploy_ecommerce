# ✅ Checklist de Deploy - VPS LocawWeb

Use este checklist para garantir que tudo está correto antes e depois do deploy.

---

## 📋 ANTES DO DEPLOY

### Preparação Local

- [ ] **Testes passando localmente**
  ```bash
  composer test
  ```

- [ ] **Código commitado no Git**
  ```bash
  git add .
  git commit -m "Preparado para deploy"
  git push
  ```

- [ ] **Arquivo `.env.example` atualizado**
  - Verificar se tem todas as variáveis necessárias

- [ ] **Arquivos sensíveis no `.gitignore`**
  - `.env` está ignorado?
  - `logs/` está ignorado?
  - `vendor/` está ignorado?

- [ ] **Sem senhas hardcoded no código**
  - Verificar se não há senhas no código fonte

---

## 🖥️ NO SERVIDOR VPS

### Configuração Inicial (Primeira vez)

- [ ] **Conectado ao servidor via SSH**
  ```bash
  ssh usuario@ip_do_servidor
  ```

- [ ] **PHP 8.2+ instalado**
  ```bash
  php -v
  ```
  Versão: ___________

- [ ] **Extensões PHP instaladas**
  - php8.2-mysql ✅
  - php8.2-mbstring ✅
  - php8.2-xml ✅
  - php8.2-curl ✅

- [ ] **MySQL/MariaDB instalado**
  ```bash
  mysql --version
  ```

- [ ] **Composer instalado**
  ```bash
  composer --version
  ```

- [ ] **Nginx ou Apache instalado**
  ```bash
  nginx -v  # ou apache2 -v
  ```

- [ ] **Git instalado**
  ```bash
  git --version
  ```

---

### Banco de Dados

- [ ] **Banco de dados criado**
  ```sql
  CREATE DATABASE aula_php_mvc;
  ```

- [ ] **Usuário do banco criado**
  ```sql
  CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'senha_forte';
  ```

- [ ] **Permissões concedidas**
  ```sql
  GRANT ALL PRIVILEGES ON aula_php_mvc.* TO 'app_user'@'localhost';
  FLUSH PRIVILEGES;
  ```

- [ ] **Schema importado**
  ```bash
  mysql -u app_user -p aula_php_mvc < database/schema.sql
  ```

---

### Deploy do Projeto

- [ ] **Diretório do projeto criado**
  ```bash
  sudo mkdir -p /var/www/aula7
  ```

- [ ] **Código no servidor**
  - [ ] Via Git: `git clone` feito
  - [ ] Via FTP: Arquivos enviados

- [ ] **Dependências instaladas**
  ```bash
  composer install --no-dev --optimize-autoloader
  ```

- [ ] **Arquivo `.env` criado e configurado**
  ```bash
  cp .env.example .env
  nano .env
  ```
  - [ ] DB_HOST configurado
  - [ ] DB_NAME configurado
  - [ ] DB_USER configurado
  - [ ] DB_PASS configurado
  - [ ] APP_ENV=production
  - [ ] APP_DEBUG=false

- [ ] **Diretório de logs criado**
  ```bash
  mkdir -p logs
  chmod 775 logs
  ```

- [ ] **Permissões configuradas**
  ```bash
  sudo chown -R www-data:www-data /var/www/aula7
  sudo chmod -R 755 /var/www/aula7
  sudo chmod -R 775 logs
  ```

---

### Servidor Web

#### Nginx

- [ ] **Arquivo de configuração criado**
  ```bash
  sudo nano /etc/nginx/sites-available/aula7
  ```

- [ ] **Site ativado**
  ```bash
  sudo ln -s /etc/nginx/sites-available/aula7 /etc/nginx/sites-enabled/
  ```

- [ ] **Configuração testada**
  ```bash
  sudo nginx -t
  ```

- [ ] **Nginx recarregado**
  ```bash
  sudo systemctl reload nginx
  ```

#### Apache

- [ ] **Virtual Host criado**
  ```bash
  sudo nano /etc/apache2/sites-available/aula7.conf
  ```

- [ ] **Site ativado**
  ```bash
  sudo a2ensite aula7.conf
  ```

- [ ] **mod_rewrite habilitado**
  ```bash
  sudo a2enmod rewrite
  ```

- [ ] **Apache recarregado**
  ```bash
  sudo systemctl reload apache2
  ```

---

## 🔒 SEGURANÇA E OTIMIZAÇÃO

- [ ] **SSL/HTTPS configurado** (Opcional mas recomendado)
  ```bash
  sudo certbot --nginx -d seudominio.com.br
  # ou
  sudo certbot --apache -d seudominio.com.br
  ```

- [ ] **Firewall configurado**
  ```bash
  sudo ufw allow 22/tcp   # SSH
  sudo ufw allow 80/tcp   # HTTP
  sudo ufw allow 443/tcp  # HTTPS
  sudo ufw enable
  ```

- [ ] **Arquivos sensíveis protegidos**
  - `.env` não acessível via web ✅
  - `vendor/` não acessível via web ✅
  - `config/` não acessível via web ✅

---

## ✅ TESTES PÓS-DEPLOY

### Funcionalidades

- [ ] **Aplicação acessível**
  - URL: _______________________
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **Página de login carrega**
  - URL: _______________________
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **Login funciona**
  - Email: admin@teste.com
  - Senha: 123456
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **Listar produtos funciona**
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **Criar produto funciona**
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **Editar produto funciona**
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **Deletar produto funciona**
  - Status: ✅ Funcionando / ❌ Erro

- [ ] **CRUD de categorias funciona**
  - Status: ✅ Funcionando / ❌ Erro

---

### Verificações Técnicas

- [ ] **Sem erros no log do PHP**
  ```bash
  sudo tail -f /var/log/php8.2-fpm.log
  ```

- [ ] **Sem erros no log do servidor web**
  ```bash
  # Nginx
  sudo tail -f /var/log/nginx/error.log
  
  # Apache
  sudo tail -f /var/log/apache2/error.log
  ```

- [ ] **Logs da aplicação sendo gerados**
  ```bash
  tail -f /var/www/aula7/logs/app.log
  ```

- [ ] **Performance adequada**
  - Tempo de resposta: ___________
  - Aceitável? ✅ Sim / ❌ Não

---

## 📝 INFORMAÇÕES DO SERVIDOR

Preencha estas informações para referência:

- **IP do Servidor**: _______________________
- **Domínio**: _______________________
- **Usuário SSH**: _______________________
- **Diretório do Projeto**: _______________________
- **Banco de Dados**: _______________________
- **Usuário do Banco**: _______________________
- **Servidor Web**: [ ] Nginx [ ] Apache
- **PHP Version**: _______________________

---

## 🆘 PROBLEMAS ENCONTRADOS

Anote aqui qualquer problema encontrado:

1. _________________________________________________
   Solução: ________________________________________

2. _________________________________________________
   Solução: ________________________________________

3. _________________________________________________
   Solução: ________________________________________

---

## ✅ CONCLUSÃO

- [ ] **Todos os itens acima marcados**
- [ ] **Aplicação funcionando corretamente**
- [ ] **Sem erros críticos**
- [ ] **Documentação atualizada**

**Deploy concluído em**: _____/_____/_____  
**Responsável**: _______________________

---

**Dica**: Guarde este checklist preenchido para referência futura!

