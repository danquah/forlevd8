# Forlev D8

## Dev requirements
* Docker [Official](https://www.docker.com/community-edition#/download) or eg. [Dinghy](https://github.com/codekitchen/dinghy)
* [Node](https://nodejs.org/en/download/)
* [Grunt](https://gruntjs.com/getting-started)
* [Composer](https://getcomposer.org/doc/00-intro.md)

## Basic install
```bash
composer install
cd web/themes/forlev2016
npm install
grunt
grunt watch
```

## Getting a database
```bash
scripts/getdb.sh
```

## Container start
```bash
scripts/docker-reset.sh
```
