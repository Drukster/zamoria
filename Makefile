.PHONY: help setup build up down restart logs clean install-backend install-frontend shell-backend shell-frontend db-migrate db-fresh test

# Цвета для вывода
YELLOW := \033[33m
GREEN := \033[32m
RED := \033[31m
BLUE := \033[34m
RESET := \033[0m

help: ## 📖 Показать список всех команд
	@echo "$(GREEN)🚀 Доступные команды:$(RESET)"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  $(BLUE)%-20s$(RESET) %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: ## 🛠️ Полная настройка проекта (первый запуск)
	@echo "$(YELLOW)🚀 Настройка монорепозитория...$(RESET)"
	@if [ ! -f .env ]; then \
		echo "$(YELLOW)📄 Копируем корневой .env файл...$(RESET)"; \
		cp .env.example .env; \
	fi
	@if [ ! -f frontend/.env ]; then \
		echo "$(YELLOW)📄 Копируем frontend .env файл...$(RESET)"; \
		cp frontend/.env.example frontend/.env; \
	fi
	@$(MAKE) build
	@$(MAKE) up
	@echo "$(YELLOW)⏳ Ожидаем запуск сервисов...$(RESET)"
	@sleep 30
	@$(MAKE) install-backend
	@$(MAKE) install-frontend
	@$(MAKE) db-migrate
	@echo ""
	@echo "$(GREEN)✅ Настройка завершена!$(RESET)"
	@$(MAKE) info

build: ## 🏗️ Собрать все Docker контейнеры
	@echo "$(YELLOW)🏗️ Собираем Docker контейнеры...$(RESET)"
	@docker-compose build

up: ## 🚀 Запустить все сервисы
	@echo "$(YELLOW)🚀 Запускаем сервисы...$(RESET)"
	@docker-compose up -d

down: ## ⏹️ Остановить все сервисы
	@echo "$(YELLOW)⏹️ Останавливаем сервисы...$(RESET)"
	@docker-compose down

restart: ## 🔄 Перезапустить все сервисы
	@echo "$(YELLOW)🔄 Перезапускаем сервисы...$(RESET)"
	@docker-compose restart

logs: ## 📋 Показать логи всех сервисов
	@docker-compose logs -f

logs-backend: ## 📋 Показать логи backend
	@docker-compose logs -f backend

logs-frontend: ## 📋 Показать логи frontend
	@docker-compose logs -f frontend

logs-db: ## 📋 Показать логи базы данных
	@docker-compose logs -f db

clean: ## 🧹 Очистить Docker (контейнеры, образы, volumes)
	@echo "$(RED)🧹 Очищаем Docker...$(RESET)"
	@docker-compose down --volumes --remove-orphans
	@docker system prune -f
	@docker volume prune -f

install-backend: ## 📦 Установить зависимости Laravel
	@echo "$(YELLOW)📦 Устанавливаем зависимости Laravel...$(RESET)"
	@docker-compose exec -T backend composer install
	@echo "$(YELLOW)🔑 Генерируем APP_KEY...$(RESET)"
	@docker-compose exec -T backend php artisan key:generate
	@echo "$(YELLOW)🔗 Создаем symbolic link для storage...$(RESET)"
	@docker-compose exec -T backend php artisan storage:link
	@echo "$(YELLOW)🧹 Очищаем кэш Laravel...$(RESET)"
	@docker-compose exec -T backend php artisan config:cache
	@docker-compose exec -T backend php artisan route:cache
	@docker-compose exec -T backend php artisan view:cache

install-frontend: ## 📦 Установить зависимости React
	@echo "$(YELLOW)📦 Устанавливаем зависимости React...$(RESET)"
	@docker-compose exec -T frontend npm install

shell-backend: ## 🐚 Войти в shell backend контейнера
	@docker-compose exec backend bash

shell-frontend: ## 🐚 Войти в shell frontend контейнера
	@docker-compose exec frontend sh

shell-db: ## 🐚 Войти в MySQL консоль
	@docker-compose exec db mysql -u laravel -p laravel

db-migrate: ## 🗃️ Запустить миграции
	@echo "$(YELLOW)🗃️ Запускаем миграции...$(RESET)"
	@docker-compose exec -T backend php artisan migrate --force

db-fresh: ## 🆕 Пересоздать базу данных с сидами
	@echo "$(YELLOW)🆕 Пересоздаем базу данных...$(RESET)"
	@docker-compose exec -T backend php artisan migrate:fresh --seed

db-seed: ## 🌱 Заполнить базу тестовыми данными
	@echo "$(YELLOW)🌱 Заполняем базу тестовыми данными...$(RESET)"
	@docker-compose exec -T backend php artisan db:seed

test: ## 🧪 Запустить тесты
	@echo "$(YELLOW)🧪 Запускаем тесты...$(RESET)"
	@docker-compose exec -T backend php artisan test

test-frontend: ## 🧪 Запустить тесты frontend
	@echo "$(YELLOW)🧪 Запускаем тесты frontend...$(RESET)"
	@docker-compose exec -T frontend npm test

queue-work: ## 🔄 Запустить обработку очередей
	@echo "$(YELLOW)🔄 Запускаем обработку очередей...$(RESET)"
	@docker-compose exec -T backend php artisan queue:work

cache-clear: ## 🧹 Очистить весь кэш Laravel
	@echo "$(YELLOW)🧹 Очищаем кэш Laravel...$(RESET)"
	@docker-compose exec -T backend php artisan cache:clear
	@docker-compose exec -T backend php artisan config:clear
	@docker-compose exec -T backend php artisan route:clear
	@docker-compose exec -T backend php artisan view:clear

status: ## 📊 Показать статус всех контейнеров
	@echo "$(GREEN)📊 Статус контейнеров:$(RESET)"
	@docker-compose ps

info: ## ℹ️ Показать информацию о сервисах
	@echo ""
	@echo "$(GREEN)🌐 Сервисы доступны по адресам:$(RESET)"
	@echo "  • $(BLUE)Основное приложение:$(RESET) http://localhost"
	@echo "  • $(BLUE)phpMyAdmin:$(RESET) http://localhost:8081"
	@echo "  • $(BLUE)Mailpit:$(RESET) http://localhost:8025"
	@echo ""
	@echo "$(GREEN)🛠️ Полезные команды:$(RESET)"
	@echo "  • $(BLUE)make logs$(RESET)                - просмотр логов"
	@echo "  • $(BLUE)make shell-backend$(RESET)       - войти в backend контейнер"
	@echo "  • $(BLUE)make shell-frontend$(RESET)      - войти в frontend контейнер"
	@echo "  • $(BLUE)make db-migrate$(RESET)          - запустить миграции"
	@echo "  • $(BLUE)make test$(RESET)                - запустить тесты"
	@echo "  • $(BLUE)make down$(RESET)                - остановить сервисы"
	@echo ""

# Развертывание в production
prod-build: ## 🚀 Сборка для production
	@echo "$(YELLOW)🚀 Сборка для production...$(RESET)"
	@docker-compose -f docker-compose.yml -f docker-compose.prod.yml build

prod-up: ## 🌐 Запуск в production режиме
	@echo "$(YELLOW)🌐 Запуск в production режиме...$(RESET)"
	@docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d