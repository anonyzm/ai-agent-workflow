env:
	cp .env.dist .env

up:
	docker compose up -d

down:
	docker compose stop

recreate: 
	docker compose up -d --force-recreate

ps:
	docker compose ps

bash:
	docker compose exec --user application app bash

build:
	docker compose build --no-cache

rebuild:
	docker compose down
	docker compose build --no-cache
	docker compose up -d

logs:
	docker compose logs --tail 50 app

clean:
	docker system prune -f
	docker volume rm
	docker volume prune -f

prune:
	docker compose down -v
	docker system prune -a