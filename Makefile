DOMAIN=www.pizzouille.localhost
CERT_DIR=./docker/nginx/certs
CERT_KEY=$(CERT_DIR)/$(DOMAIN)-key.pem
CERT_CRT=$(CERT_DIR)/$(DOMAIN).pem
PHP_CONTAINER=pizzouille-php

.PHONY: install up down build clean logs bash certs composer db

install: down certs up wait-db wait-rabbitmq composer db

up:
	@echo "🚀 Starting Docker containers..."
	docker compose --env-file .env.docker up -d --build

down:
	@echo "🛑 Stopping Docker containers..."
	docker compose --env-file .env.docker down

build:
	@echo "🏗️ Building Docker images..."
	docker compose --env-file .env.docker build

clean: down
	@echo "🧹 Removing Docker volumes..."
	docker volume rm db_data || true

logs:
	docker compose --env-file .env.docker logs -f

bash:
	docker exec -it $(PHP_CONTAINER) bash

certs:
	@echo "🔐 Generating SSL certificates for $(DOMAIN)..."
	@mkdir -p $(CERT_DIR)
	@if ! command -v mkcert >/dev/null 2>&1; then \
		echo "❌ mkcert is not installed. Please install it from https://mkcert.dev"; \
		exit 1; \
	fi
	@mkcert -install
	@mkcert -key-file $(CERT_KEY) -cert-file $(CERT_CRT) $(DOMAIN)
	@echo "✅ Certificates created at $(CERT_DIR)"

## Install dependencies
composer:
	@echo "📦 Installing dependencies..."
	docker exec -u www-data $(PHP_CONTAINER) composer install --no-interaction --prefer-dist --optimize-autoloader

db:
	@echo "💾 Creating database if not exists..."
	docker exec -u www-data $(PHP_CONTAINER) bin/console doctrine:database:create --if-not-exists

wait-db:
	@echo "⏳ Waiting for PostgreSQL to be ready..."
	@until docker exec pizzouille-db pg_isready -h localhost -p 5432; do \
		sleep 2; \
	done
	@echo "✅ PostgreSQL is up!"

wait-rabbitmq:
	@echo "⏳ Waiting for RabbitMQ to be ready..."
	@until docker exec $(PHP_CONTAINER) sh -c "nc -z rabbitmq 5672"; do \
		sleep 2; \
	done
	@echo "✅ RabbitMQ is up!"