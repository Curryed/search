run:
	docker-compose kill || echo "$(COLOR_IYELLOW)Don't worry there is just nothing to kill ;)$(COLOR_END)"
	@echo "$(COLOR_IGREEN)Starting up containers for $(PROJECT_NAME)...$(COLOR_END)"
	@echo "$(COLOR_IGREEN)Just hit Ctrl-C to stop the project ;)$(COLOR_END)"
	docker-compose up --remove-orphans

elastic:
	docker-compose exec --user root -e LINES=$$(tput lines) -e COLUMNS=$$(tput cols) elastic bash

php:
	docker-compose exec --user www-user -e LINES=$$(tput lines) -e COLUMNS=$$(tput cols) php bash

docker run --rm --env discovery.type=single-node --env cluster.name=docker-cluster --env bootstrap.memory_lock=false --env xpack.security.enabled=true --env "ES_JAVA_OPTS=-Xms512m -Xmx512m" --env ELASTIC_PASSWORD=admin -p 9200:9200 docker.elastic.co/elasticsearch/elasticsearch:8.6.2
docker run --rm --env discovery.type=single-node --env bootstrap.memory_lock=false --env xpack.security.enabled=true --env ELASTIC_PASSWORD=admin -p 9200:9200 docker.elastic.co/elasticsearch/elasticsearch:8.6.2
