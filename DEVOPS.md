# Guia DevOps - Sistema de Gerenciamento de Produtos

Este documento descreve todas as práticas de DevOps implementadas no projeto.

## 📋 Índice

1. [Integração Contínua (CI)](#integração-contínua-ci)
2. [Entrega Contínua (CD)](#entrega-contínua-cd)
3. [Testes Automatizados](#testes-automatizados)
4. [Containerização (Docker)](#containerização-docker)
5. [Monitoramento e Logs](#monitoramento-e-logs)
6. [Versionamento](#versionamento)
7. [Qualidade de Código](#qualidade-de-código)

---

## 🔄 Integração Contínua (CI)

O projeto utiliza **GitHub Actions** para automatizar testes e verificações de qualidade de código.

### Pipeline CI

O pipeline está configurado em `.github/workflows/ci.yml` e executa:

1. **Testes Unitários e de Integração**
   - Executa PHPUnit para validar funcionalidades
   - Gera relatório de cobertura de código
   - Upload para Codecov (opcional)

2. **Análise Estática**
   - PHPStan para detecção de erros e problemas de tipo
   - PHP CodeSniffer para verificação de padrões de código (PSR-12)

3. **Análise de Segurança**
   - Verificação de dependências vulneráveis via `composer audit`

4. **Build Docker**
   - Construção da imagem Docker em cada push para `main`

### Como funciona

- **Trigger**: Executa automaticamente em:
  - Push para branches `main` ou `develop`
  - Pull Requests para `main` ou `develop`

- **Ambiente**: Ubuntu Latest com PHP 8.2
- **Serviços**: MySQL 8.0 para testes de integração

### Comandos Locais

```bash
# Executar o pipeline completo localmente
composer ci

# Ou executar cada etapa separadamente
composer test          # Testes
composer phpstan       # Análise estática
composer cs-check      # Verificação de código
```

---

## 🚀 Entrega Contínua (CD)

### Estratégia de Deploy

O pipeline de CD está configurado para deploy automático quando:
- Código é mergeado na branch `main`
- Todos os testes passam
- Análises de segurança não encontram vulnerabilidades críticas

### Ambientes

- **Development**: Branch `develop` - ambiente de desenvolvimento
- **Production**: Branch `main` - ambiente de produção

### Deploy Manual

Para deploy manual, você pode usar:

```bash
# Build da imagem Docker
docker build -t aula7-mvc:latest .

# Ou usar Docker Compose
docker-compose up -d --build
```

---

## 🧪 Testes Automatizados

### Estrutura de Testes

```
tests/
├── Unit/           # Testes unitários
│   └── ModelTest.php
└── Integration/    # Testes de integração
    └── ApiTest.php
```

### Executar Testes

```bash
# Todos os testes
composer test

# Com cobertura de código
composer test-coverage

# Acessar relatório de cobertura
# Abra: coverage/index.html
```

### Configuração

Os testes são configurados em `phpunit.xml`:
- Ambiente de teste separado
- Banco de dados de teste: `aula_php_mvc_test`
- Cobertura de código habilitada

### Boas Práticas

- ✅ Testes unitários para Models
- ✅ Testes de integração para APIs
- ✅ Validação de dados
- ✅ Cobertura mínima de 60% (recomendado)

---

## 🐳 Containerização (Docker)

### Docker Compose

O projeto inclui `docker-compose.yml` com três serviços:

1. **web** (PHP 8.2 + Apache)
   - Porta: 8080
   - Volume: código fonte montado

2. **db** (MySQL 8.0)
   - Porta: 3306
   - Volume persistente para dados
   - Script SQL inicial executado automaticamente

3. **phpmyadmin**
   - Porta: 8081
   - Interface web para gerenciar banco de dados

### Comandos Docker

```bash
# Iniciar todos os serviços
docker-compose up -d

# Ver logs
docker-compose logs -f

# Parar serviços
docker-compose down

# Rebuild após mudanças
docker-compose up -d --build

# Acessar container
docker exec -it aula7_web bash
```

### Dockerfile

O `Dockerfile` está otimizado para produção:
- Base: PHP 8.2 Apache
- Extensões necessárias instaladas
- Composer configurado
- Health check configurado

### Variáveis de Ambiente

Configure no `.env` ou `docker-compose.yml`:
- `DB_HOST`: Host do banco (db no Docker)
- `DB_NAME`: Nome do banco
- `DB_USER`: Usuário
- `DB_PASS`: Senha
- `APP_ENV`: Ambiente (development/production)

---

## 📊 Monitoramento e Logs

### Sistema de Logging

O projeto inclui a classe `App\Logger` para logging estruturado:

```php
use App\Logger;

// Diferentes níveis de log
Logger::debug('Mensagem de debug', ['context' => 'data']);
Logger::info('Operação realizada');
Logger::warning('Atenção necessária');
Logger::error('Erro ocorrido', ['error_code' => 500]);
Logger::critical('Falha crítica');

// Métricas
Logger::metric('response_time', 150.5, 'ms');

// Requisições HTTP
Logger::request('GET', '/produtos', 200, 0.15);
```

### Localização dos Logs

- **Arquivo**: `logs/app.log`
- **Formato**: Estruturado com timestamp, nível e contexto JSON

### Métricas

O sistema registra automaticamente:
- Tempo de resposta de requisições
- Erros e exceções
- Operações críticas do sistema

### Health Checks

O Dockerfile inclui health check configurado:
- Verifica se a aplicação está respondendo
- Intervalo: 30 segundos
- Timeout: 3 segundos

---

## 📝 Versionamento

### Git

O projeto utiliza Git para controle de versão.

### .gitignore

Arquivos e pastas ignorados:
- `/vendor/` - Dependências
- `/logs/` - Arquivos de log
- `.env` - Variáveis de ambiente locais
- Arquivos de IDE
- Cache e arquivos temporários

### Estratégia de Branches

- **main**: Produção (protegida)
- **develop**: Desenvolvimento
- **feature/***: Novas funcionalidades
- **hotfix/***: Correções urgentes

### Commits

Seguir convenções de commits:
- `feat:` Nova funcionalidade
- `fix:` Correção de bug
- `docs:` Documentação
- `test:` Testes
- `refactor:` Refatoração
- `ci:` CI/CD

---

## 🔍 Qualidade de Código

### Ferramentas

1. **PHPStan** - Análise estática
   ```bash
   composer phpstan
   ```

2. **PHP CodeSniffer** - Padrões de código
   ```bash
   composer cs-check  # Verificar
   composer cs-fix     # Corrigir automaticamente
   ```

### Padrões

- **PSR-12**: Padrão de codificação PHP
- **PSR-4**: Autoloading
- **Type hints**: Tipos explícitos quando possível
- **Documentação**: PHPDoc para classes e métodos públicos

### Code Review

Antes de fazer merge:
1. ✅ Todos os testes passam
2. ✅ PHPStan sem erros
3. ✅ CodeSniffer sem problemas
4. ✅ Cobertura de código mantida
5. ✅ Documentação atualizada

---

## 🛠️ Scripts Úteis

### Composer Scripts

```bash
composer serve          # Iniciar servidor PHP built-in
composer test           # Executar testes
composer test-coverage  # Testes com cobertura
composer phpstan        # Análise estática
composer cs-check       # Verificar código
composer cs-fix         # Corrigir código
composer ci             # Pipeline completo (test + phpstan + cs-check)
```

---

## 📚 Recursos Adicionais

### Documentação
- [PHPUnit](https://phpunit.de/documentation.html)
- [Docker](https://docs.docker.com/)
- [GitHub Actions](https://docs.github.com/en/actions)
- [PSR-12](https://www.php-fig.org/psr/psr-12/)

### Ferramentas Recomendadas
- **Insomnia/Postman**: Testar APIs
- **phpMyAdmin**: Gerenciar banco (já incluído no Docker)
- **VS Code**: Editor com extensões PHP
- **Git**: Controle de versão

---

## 🎯 Próximos Passos

Melhorias futuras sugeridas:

1. **Monitoramento Avançado**
   - Integração com Prometheus/Grafana
   - Alertas automáticos

2. **Deploy Automatizado**
   - Integração com AWS/Azure/GCP
   - Deploy em staging antes de produção

3. **Backup Automatizado**
   - Backup diário do banco de dados
   - Retenção configurável

4. **Performance**
   - Cache (Redis)
   - CDN para assets estáticos

5. **Segurança**
   - Dependabot para atualizações
   - Verificação de vulnerabilidades automática

---

## ⚠️ Troubleshooting

### Problemas Comuns

**Testes falhando:**
```bash
# Verificar configuração do banco de teste
# Criar banco: aula_php_mvc_test
# Executar: composer test
```

**Docker não inicia:**
```bash
# Verificar portas disponíveis
# Parar outros serviços nas portas 8080, 3306, 8081
docker-compose down
docker-compose up -d
```

**Erros de permissão:**
```bash
# Linux/Mac
chmod -R 755 logs/
chown -R www-data:www-data logs/
```

---

**Última atualização**: 2025-11-05

