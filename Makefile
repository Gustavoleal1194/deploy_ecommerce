# Makefile para automação de tarefas DevOps

.PHONY: help install test test-coverage docker-up docker-down docker-build docker-logs ci clean

help: ## Mostra esta mensagem de ajuda
	@echo "Comandos disponíveis:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Instala dependências do projeto
	composer install

test: ## Executa testes automatizados
	composer test

test-coverage: ## Executa testes com cobertura de código
	composer test-coverage

docker-up: ## Inicia containers Docker
	docker-compose up -d

docker-down: ## Para containers Docker
	docker-compose down

docker-build: ## Build das imagens Docker
	docker-compose build

docker-logs: ## Mostra logs dos containers
	docker-compose logs -f

docker-restart: ## Reinicia containers Docker
	docker-compose restart

ci: ## Executa pipeline CI completo (testes + análise)
	composer ci

clean: ## Limpa arquivos temporários e cache
	rm -rf vendor/
	rm -rf coverage/
	rm -rf .phpunit.cache/
	rm -rf logs/*.log

setup: install docker-up ## Setup completo do projeto
	@echo "✅ Projeto configurado com sucesso!"
	@echo "🌐 Acesse: http://localhost:8080"
	@echo "📊 phpMyAdmin: http://localhost:8081"

