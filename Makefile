DOCKER_COMPOSE ?= docker compose

install-dependencies:
	docker run -v ${PWD}:/opt/www -p 9501:9501 -w /opt/www --rm hyperf/hyperf:8.3-alpine-v3.22-swoole composer install

install:
	cp .env.example .env
	@make install-dependencies

up:
	$(DOCKER_COMPOSE) up -d

migrate-seed:
	$(DOCKER_COMPOSE) exec app php bin/hyperf.php migrate:fresh --seed
