.PHONY: help build up down restart logs shell composer npm migrate seed test swagger clean install

# Default target
.DEFAULT_GOAL := help

# Variables
COMPOSE := docker compose -f docker/docker-compose.yml
PHP := $(COMPOSE) exec app
ARTISAN := $(PHP) php artisan

help: ## Show command help
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker containers
	$(COMPOSE) build --no-cache

up: ## Start containers
	$(COMPOSE) up -d

down: ## Stop and remove containers
	$(COMPOSE) down

restart: ## Restart containers
	$(COMPOSE) restart

logs: ## Show container logs
	$(COMPOSE) logs -f

shell: ## Enter app container shell
	$(PHP) sh

composer-install: ## Install Composer dependencies
	$(PHP) composer install

composer-update: ## Update Composer dependencies
	$(PHP) composer update

composer-dump: ## Update autoload
	$(PHP) composer dump-autoload

npm-install: ## Install NPM dependencies
	$(PHP) npm install

npm-build: ## Build frontend files
	$(PHP) npm run build

npm-dev: ## Run Vite in development mode
	$(PHP) npm run dev

key: ## Generate application key
	$(ARTISAN) key:generate

migrate: ## Run migrations
	$(ARTISAN) migrate

migrate-fresh: ## Drop and re-run migrations
	$(ARTISAN) migrate:fresh

migrate-rollback: ## Rollback migrations
	$(ARTISAN) migrate:rollback

seed: ## Run seeders
	$(ARTISAN) db:seed

migrate-seed: migrate seed ## Run migrations and seed

cache-clear: ## Clear cache
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear

cache: ## Build cache
	$(ARTISAN) config:cache
	$(ARTISAN) route:cache
	$(ARTISAN) view:cache

optimize: cache composer-dump ## Optimize application

swagger-generate: ## Generate Swagger documentation
	$(ARTISAN) l5-swagger:generate

swagger: swagger-generate ## Generate and view Swagger documentation

test: ## Run tests
	$(ARTISAN) test

test-coverage: ## Run tests with coverage
	$(PHP) php artisan test --coverage

install: build up composer-install npm-install key migrate-seed swagger-generate ## Full project installation
	@echo "Installation complete! Application is available at http://localhost:8002"
	@echo "Swagger UI is available at http://localhost:8002/api/documentation"

clean: down ## Complete cleanup (containers and volumes)
	$(COMPOSE) down -v
	docker system prune -f

fresh: migrate-fresh seed swagger-generate ## Drop and recreate database

setup: install ## Initial project setup

ps: ## Show container status
	$(COMPOSE) ps

mysql: ## Enter MySQL
	$(COMPOSE) exec mysql mysql -u laravel -p

redis-cli: ## Enter Redis CLI
	$(COMPOSE) exec redis redis-cli

queue-work: ## Run queue worker
	$(ARTISAN) queue:work

queue-listen: ## Run queue listener
	$(ARTISAN) queue:listen

schedule: ## Run scheduled tasks
	$(ARTISAN) schedule:work

tinker: ## Run Tinker
	$(ARTISAN) tinker

permissions: ## Set file permissions
	$(PHP) chown -R www-data:www-data /var/www/html
	$(PHP) chmod -R 755 /var/www/html/storage
	$(PHP) chmod -R 755 /var/www/html/bootstrap/cache
