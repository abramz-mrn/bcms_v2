SHELL := /bin/bash
COMPOSE_BASE := -f infra/docker/compose/docker-compose.yml
COMPOSE_DEV := -f infra/docker/compose/docker-compose.dev.yml
COMPOSE_PROD := -f infra/docker/compose/docker-compose.prod.yml

.PHONY: install up-dev up-prod down-dev down-prod migrate seed logs-api logs-web lint-backend lint-frontend test-backend build-web build-api

install:
	@cd apps/api && composer install
	@cd apps/web && npm install

up-dev:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) up -d

up-prod:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_PROD) up -d

down-dev:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) down

down-prod:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_PROD) down

migrate:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan migrate

seed:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan db:seed

logs-api:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) logs -f api

logs-web:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) logs -f web

lint-backend:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api ./vendor/bin/pint

lint-frontend:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec web npm run lint

test-backend:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan test || true

build-web:
	@cd apps/web && npm run build

build-api:
	@docker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan octane:status || true
