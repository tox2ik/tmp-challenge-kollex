#!/bin/sh -e

export COMPOSER_HOME=/.composer
composer install --quiet --no-interaction --working-dir /var/www/kassesystem
composer install --quiet --no-interaction --working-dir /var/www/kassesystem-api
