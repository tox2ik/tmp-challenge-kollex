all: unit-once schema desc_product import_products display

run: image
	docker run --rm  -it -v $(shell pwd):/code kollex:1 ## make -C /code

unit-once:
	php -dauto_prepend_file=.meta/xdebug-trace-filter.php ./vendor/bin/phpunit \
		--testdox \
		--no-coverage \
		--no-logging \
		--stop-on-error --stop-on-failure \
		--testsuite=unit
trace:
	.meta/find-latest /tmp/xdebug | grep --colour=auto trace | tail -n1 | xargs sed -f .meta/strip-trace-paths.sed
schema:
	rm -f var/db.sqlite || true
	php 1>/dev/null \
		-dauto_prepend_file=.meta/xdebug-trace-filter.php \
		vendor/bin/doctrine orm:schema-tool:update --force --dump-sql
desc_product:
	sqlite3 var/db.sqlite '.schema product'  |\
	   	sed 's/(/(\n/; s/,/\n/g'
import_products:
	php main.php importProducts
display:
	@php main.php displayProducts | jq .

## ## ##
## dragons ahead.
## ## ##

make build: image
	@true

image:
	docker build .meta/docker -t kollex:1


import:
	php -dauto_prepend_file=.meta/xdebug-trace-filter.php main.php importProducts





charts:
	for i in doc/*puml; do plantuml -tpng -p < $$i > doc/$$(basename $$i .puml).png; done

lint:
	vendor/bin/phpcs -p --extensions=php --standard=PSR12 --error-severity=1 --warning-severity=0 -- src test

beautify:
	vendor/bin/phpcbf -p --extensions=php --standard=PSR12 --error-severity=1 --warning-severity=0 -- src test


select_product:
	sqlite3 var/db.sqlite 'select * from product'

unit:
	bash .meta/run-it.sh php -dauto_prepend_file=.meta/xdebug-trace-filter.php ./vendor/bin/phpunit \
		--no-coverage \
		--no-logging \
		--stop-on-error --stop-on-failure \
		--testsuite=unit


coverage:
	@mkdir -p var/log
	phpunit --prepend .meta/xdebug-coverage-filter.php \
		--coverage-html var/log/coverage-report-$$(date --rfc-3339=date)

coverage-text:
	@mkdir -p var/log
	phpunit --prepend .meta/xdebug-coverage-filter.php \
		--coverage-txt var/log/coverage-report-$$(date --rfc-3339=date)

deps:
	sudo apt-get install -y php7.2-xml php7.2-mbstring php7.2-pdo php7.2-sqlite

clean:
	rm -fr var/db.sqlite
	rm -fr var/log
	rm -fr vendor

