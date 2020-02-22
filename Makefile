# ##################### #
# DOCKER COMMAND HELPER #
# ##################### #

#
# INIT PROJECT
#
.PHONY: init
init:
	make .env
	make start
	make install-deps
	make db-create
	make db-update
	make openssl genrsa -out config/jwt/private.pem -des3 -passout pass:6EQUJ5wow! 4096
	make openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem -passin pass:6EQUJ5wow!

#
# File Creation
#
.env:
		cp .env.dist .env

#
# Composer Action
#

.PHONY: install-deps
install-deps:
		docker-compose run --rm php composer install

.PHONY: update-deps
update-deps:
		docker-compose run --rm php composer update

#
# DB ACTION
#

.PHONY: db-create
db-create:
	docker-compose run --rm php php bin/console d:d:c

.PHONY:
db-update: db-update
	docker-compose run --rm php php bin/console d:s:u -f


#
# START / STOP CONTAINER
#

.PHONY: start
start:
		docker-compose up -d

.PHONY: stop
stop:
		docker-compose stop


#
# OPEN TERM IN CONTAINER
#

.PHONY: php-term
php-term:
		docker exec -it back_php_1 /bin/bash

.PHONY: sql-term
sql-term:
		docker exec -it back_mysql_1 /bin/bash

.PHONY: ngx-term
ngx-term:
		docker exec -it back_nginx_1 /bin/bash
