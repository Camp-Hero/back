CampHero
===================

CampHero make by the implementation of a dockerize symfony stack using php-fpm , mysql and nginx.
Continuous integration with travis-ci.
Implementation of Symfony and Nginx Syslog for log transmission on UDP.

Prerequisites
------------
- install docker-ce
- install docker-compose

Used docker images
------------
- nginx:1.15.5-alpine
- custom php ( based on php:7.2.11-fpm ) (cf /docker/php/Dockerfile)
- mysql:5.7


Install
------------
From project path run the following command:
```
$  docker-compose pull
$  make .env
$  docker pull composer/composer
$  docker run --rm -v $(pwd):/app composer/composer install
```
Once done edit .env with your custom variables

Start
------------
```
$  make start
```
Browse symfony app on : http://localhost:8081/
Browse the kibana ihm on : http://localhost:5601

Stop
------------
```
$  make stop
```

fluentD listen port:
------------
- 5140 ( type: syslog , format: nginx )
- 24224 ( type: syslog , format: syslog, message_format: rfc5424 )

Useful links
------------
- nginx : https://nginx.org/en/docs/
- mysql : https://dev.mysql.com/doc/refman/5.7/en/
- php-fpm: http://php.net/manual/en/install.fpm.php
- syslog protocole : https://tools.ietf.org/html/rfc5424#section-6.2.3.1
- monolog Handler : https://github.com/Seldaek/monolog/tree/master/src/Monolog/Handler
- monolog configuration : https://github.com/symfony/monolog-bundle/blob/master/DependencyInjection/Configuration.php
- ELK stack : https://www.elastic.co/elk-stack
- kibana : https://www.elastic.co/guide/en/kibana/6.4/index.html
- elasticsearch : https://www.elastic.co/guide/en/elasticsearch/reference/current/index.html
- fluentd : https://docs.fluentd.org/v1.0/articles/quickstart