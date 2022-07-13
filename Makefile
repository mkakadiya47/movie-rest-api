.PHONY: install start stop php-command test-case

MKFILE_PATH := $(abspath $(lastword $(MAKEFILE_LIST)))
MKFILE_DIR := $(dir $(MKFILE_PATH))
DOCKER_PATH := $(MKFILE_DIR)
APP_PATH := $(MKFILE_DIR)app
DOTENV_FILE_PATH := $(APP_PATH)/.env
JWT_KEYS_PATH := $(APP_PATH)/config/jwt
JWT_SECRET_MIN_LENGTH := 4
JWT_SECRET_MAX_LENGTH := 1023

define get_env
	$$(grep APP_ENV $(DOTENV_FILE_PATH) | cut -d '=' -f2)
endef

install:
	@echo Building...
	sudo chmod 755 -R mysql/*
	docker-compose up --build -d; \
	cd ../ && cd $(APP_PATH); \
	composer install; \
	docker exec -it "$$(docker-compose ps | grep "php" | awk '{print $$1}')" bin/console lexik:jwt:generate-keypair --overwrite -n; \
	docker exec -it "$$(docker-compose ps | grep "php" | awk '{print $$1}')" bin/console doctrine:database:drop --if-exists --force; \
	docker exec -it "$$(docker-compose ps | grep "php" | awk '{print $$1}')" bin/console doctrine:database:create --if-not-exists; \
	docker exec -it "$$(docker-compose ps | grep "php" | awk '{print $$1}')" bin/console doc:mig:mig --env=dev -n; \
	docker exec -it "$$(docker-compose ps | grep "php" | awk '{print $$1}')" bin/console  doctrine:fixtures:load -n
	@echo Installation successful


start:
	@echo Starting...
	@cd $(DOCKER_PATH); \
	docker-compose up --build -d
	@echo Done.

stop:
	@echo Stopping...
	@cd $(DOCKER_PATH); \
	docker-compose down
	@echo Done.

test-case:
	@echo Start TestCases...
	@cd $(DOCKER_PATH); \
	docker exec -it "$$(docker-compose ps | grep "php" | awk '{print $$1}')" vendor/bin/phpunit tests/;
	@echo Done.

restart:
	@echo Restarting...
	@make stop --no-print-directory
	@make start --no-print-directory
