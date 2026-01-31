.PHONY: help install test quality docker-up docker-down clean

help:
	@echo "API Rate Limiter Bundle - Commands"
	@echo "  make install    - Install dependencies"
	@echo "  make test       - Run tests"
	@echo "  make quality    - Run all quality checks"
	@echo "  make docker-up  - Start Docker containers"

install:
	composer install

test:
	vendor/bin/phpunit

phpstan:
	vendor/bin/phpstan analyse

psalm:
	vendor/bin/psalm

cs-fix:
	vendor/bin/php-cs-fixer fix

quality: cs-fix phpstan psalm test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

clean:
	rm -rf vendor coverage .phpunit.cache
