SHELL := /bin/bash
COMPOSE_BASE := -f infra/docker/compose/docker-compose.yml
COMPOSE_DEV := -f infra/docker/compose/docker-compose.dev.yml
COMPOSE_PROD := -f infra/docker/compose/docker-compose.prod.yml

.PHONY: install up-dev up-prod down-dev down-prod migrate seed logs-api logs-web lint-backend lint-frontend test-backend build-web build-api

install:
\tcd apps/api && composer install
\tcd apps/web && npm install

up-dev:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) up -d

up-prod:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_PROD) up -d

down-dev:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) down

down-prod:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_PROD) down

migrate:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan migrate

seed:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan db:seed

logs-api:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) logs -f api

logs-web:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) logs -f web

lint-backend:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api ./vendor/bin/pint

lint-frontend:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec web npm run lint

test-backend:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan test || true

build-web:
\tcd apps/web && npm run build

build-api:
\tdocker compose $(COMPOSE_BASE) $(COMPOSE_DEV) exec api php artisan octane:status || true
