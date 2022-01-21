.DEFAULT_GOAL := list
args = `echo "$(filter-out $@,$(MAKECMDGOALS))"`

## PROJECT COMMANDS ##

help: ## Show command list
	@make list

install: ## Load composer/node dependencies and add local files
	@docker-compose exec php composer install
	@docker-compose exec php yarn install
	@docker-compose exec php yarn dev
	@make override-vendors

asset: ## Compile assets (styles and javacript)
	@docker-compose exec php yarn dev

watch: ## Watch for assets changes with webpack
	@docker-compose exec php yarn watch

php-cli: ## Get into php container console
	@docker-compose exec php bash

nginx-cli: ## Get into nginx container console
	@docker-compose exec nginx sh

pg-cli : ## Get into postgres container console
	@docker-compose exec postgres bash

hooks: ## Install git hooks scripts locally (location: .git/hooks)
	@cp bin/commit-msg .git/hooks/commit-msg

clear-hooks: ## Remove installed git hooks
	@rm .git/hooks/commit-msg

clear-app: ## Clear vendor folders, cache, compiled assets
	@rm -Rf node_modules/ vendor/ var/* public/build/

override-vendors: #[Ignore] Edit vendor files
	@cp custom-vendor/symfony/make-controller/MakeController.php vendor/symfony/maker-bundle/src/Maker/MakeController.php
	@cp custom-vendor/symfony/make-crud/MakeCrud.php vendor/symfony/maker-bundle/src/Maker/MakeCrud.php
	@cp custom-vendor/symfony/make-crud/CrudController.tpl.php vendor/symfony/maker-bundle/src/Resources/skeleton/crud/controller/Controller.tpl.php


### DOCKER COMMANDS ###

start: ### Run all docker services
	@docker-compose up -d
	@echo "Server running at http://localhost:8080/"
	@sleep 1 && open "http://localhost:8080/"

build: ### Build docker-compose
	@docker-compose build --no-cache

stop: ### Stop all docker services
	@docker-compose stop

restart: ### Restart all docker services
	@docker-compose stop
	@docker-compose up -d

down: ### Stop and remove all docker services
	@docker-compose down

logs: ### Show logs for all services (5 last lines)
	@docker-compose logs --tail=5

status: ### Show services status
	@docker-compose ps

reset-volumes: ### Remove all docker volumes related to this project : database and cache (Do not run this command while running services)
	@docker volume rm dinofix_app-cache dinofix_db-data


#### SYMFONY COMMANDS ####

run: #### Run symfony command : php bin/console [args..] (ex: make run "make:entity --regenerate")
	@echo "Running : php bin/console $(args)"
	@docker-compose exec php bin/console $(args)

entity: #### Run make:entity
	@docker-compose exec php bin/console make:entity

controller: #### Run make:controller
	@docker-compose exec php bin/console make:controller

crud: #### Run make:crud
	@docker-compose exec php bin/console make:crud

ddd: #### Run doctrine:database:drop --force
	@docker-compose exec php bin/console doctrine:d:d --force

ddc: #### Run doctrine:database:create --force
	@docker-compose exec php bin/console doctrine:d:c

dsu: #### Run doctrine:schema:update --force
	@docker-compose exec php bin/console doctrine:schema:update --force

migrate: #### Run doctrine:migrations:migrate
	@docker-compose exec php bin/console doctrine:migrations:migrate

cache-clear: #### Run cache:clear
	@docker-compose exec php bin/console cache:clear

db-check: #### Run doctrine:migrations:status (Check database connection)
	@docker-compose exec php bin/console doctrine:migrations:status

routes: #### Run debug:router
	@docker-compose exec php bin/console debug:router

fixtures: #### Run doctrine:fixtures:load
	@docker-compose exec php bin/console doctrine:fixtures:load

### Display Makefile command list
list:
	@echo "Usage: make [command]"
	@grep '^##[^#].*##$/' Makefile | sed 's/##/------------/g'
	@grep "^.*: ##[^#]" Makefile | sed 's/^\(.*\): ## \(.*\)/\1 \t \2/g' | expand -t30 | tail -r | tail -n +2| tail -r
	@grep '^###[^#].*###$/' Makefile | sed 's/###/------------/g'
	@grep "^.*: ###[^#]" Makefile | sed 's/^\(.*\): ### \(.*\)/\1 \t \2/g' | expand -t30 | tail -r | tail -n +2| tail -r
	@grep '^####[^#].*####$/' Makefile | sed 's/####/------------/g'
	@grep "^.*: ####[^#]" Makefile | sed 's/^\(.*\): #### \(.*\)/\1 \t \2/g' | expand -t30 | tail -r | tail -n +2| tail -r