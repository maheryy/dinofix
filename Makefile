.DEFAULT_GOAL := list
args = `echo "$(filter-out $@,$(MAKECMDGOALS))"`

## PROJECT COMMMANDS ##
help: ## Show command list
	@make list

install: ## Load composer dependencies
	@make hooks
	@composer install

hooks: ## Install git hooks scripts locally (location: .git/hooks)
	@cp bin/commit-msg .git/hooks/commit-msg

reset-hooks: ## Remove installed git hooks
	@rm .git/hooks/commit-msg

php-cli: ## Get into php container console
	@docker-compose exec php bash

nginx-cli: ## Get into nginx container console
	@docker-compose exec nginx sh

pg-cli : ## Get into postgres container console
	@docker-compose exec postgres bash

### DOCKER COMMMANDS ###

start: ### Run all docker services
	@docker-compose up -d
	@echo "Server running at http://localhost:8080/"
	@sleep 2 && open "http://localhost:8080/"

build: ### Build docker-compose
	@docker-compose build

stop: ### Stop all docker services
	@docker-compose stop

restart: ### Restart all docker services
	@docker-compose stop
	@docker-compose up -d

down: ### Stop and remove all docker services
	@docker-compose down

logs: ### Show logs for all services (5 last lines)
	@docker-compose logs --tail=5

status: ### Show services statuse
	@docker-compose ps

reset-volumes: ### Remove all docker volumes related to this project : database and cache (Do not run this command while running services)
	@docker volume rm dinofix_app-cache dinofix_db-data


#### SYMFONY COMMMANDS ####

run: #### Run symfony command : php bin/console [args..] (ex: make run "make:entity --regenerate")
	@echo "Running : php bin/console $(args)"
	@docker-compose exec php bin/console $(args)

entity: #### Run make:entity
	@docker-compose exec php bin/console make:entity

controller: #### Run make:controller
	@docker-compose exec php bin/console make:controller

migration: #### Run make:migration
	@docker-compose exec php bin/console make:migration

migrate: #### Run doctrine:migrations:migrate
	@docker-compose exec php bin/console doctrine:migrations:migrate

clear: #### Run doctrine:migrations:migrate
	@docker-compose exec php bin/console cache:clear

db-check: #### Run doctrine:migrations:status (Check database connection)
	@docker-compose exec php bin/console doctrine:migrations:status

### Display Makefile command list
list:
	@echo "Usage: make [command]"
	@grep '^##[^#].*##$/' Makefile | sed 's/##/------------/g'
	@grep "^.*: ##[^#]" Makefile | sed 's/^\(.*\): ## \(.*\)/\1 \t \2/g' | expand -t30 | tail -r | tail -n +2| tail -r
	@grep '^###[^#].*###$/' Makefile | sed 's/###/------------/g'
	@grep "^.*: ###[^#]" Makefile | sed 's/^\(.*\): ### \(.*\)/\1 \t \2/g' | expand -t30 | tail -r | tail -n +2| tail -r
	@grep '^####[^#].*####$/' Makefile | sed 's/####/------------/g'
	@grep "^.*: ####[^#]" Makefile | sed 's/^\(.*\): #### \(.*\)/\1 \t \2/g' | expand -t30 | tail -r | tail -n +2| tail -r