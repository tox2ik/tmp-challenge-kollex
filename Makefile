all: schema desc_product import display

import:
	php -dauto_prepend_file=.meta/xdebug-trace-filter.php main.php importProducts

display:
	@php main.php displayProducts | jq .

trace:
	.meta/find-latest /tmp/xdebug | grep --colour=auto trace | tail -n1 | xargs sed -f .meta/strip-trace-paths.sed


charts:
	for i in doc/*puml; do plantuml -tpng -p < $$i > doc/$$(basename $$i .puml).png; done 

lint:
	vendor/bin/phpcs -p --extensions=php --standard=PSR12 --error-severity=1 --warning-severity=0 -- src test

beautify:
	vendor/bin/phpcbf -p --extensions=php --standard=PSR12 --error-severity=1 --warning-severity=0 -- src test

schema:
	rm var/db.sqlite || true
	php 1>/dev/null \
		-dauto_prepend_file=.meta/xdebug-trace-filter.php \
		vendor/bin/doctrine orm:schema-tool:update --force --dump-sql

select_product:
	sqlite var/db.sqlite 'select * from product'

desc_product:
	sqlite var/db.sqlite '.schema product'  |\
	   	sed 's/(/(\n/; s/,/\n/g'


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

deps:
	sudo apt-get install -y php7.2-xml php7.2-mbstring php7.2-pdo php7.2-sqlite

clean: 
	rm -fr var/db.sqlite
	rm -fr var/log
