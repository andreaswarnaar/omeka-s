.PHONY: up

up:
	@bash docker/docker-env.sh start

setup:
	bash docker/docker-env.sh build
	@bash docker/docker-env.sh up -d
	@bash docker/docker-env.sh exec composer install
	@bash docker/docker-env.sh exec npm install
	@bash docker/docker-env.sh exec npx gulp init


down:
	bash docker/docker-env.sh down
