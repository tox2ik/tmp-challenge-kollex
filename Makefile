import:
	php -dauto_prepend_file=.meta/xdebug-trace-filter.php main.php importProducts

trace:
	.meta/find-latest /tmp/xdebug | grep --colour=auto trace | tail -n1 | xargs sed -f .meta/strip-trace-paths.sed

all:
	php main.php


display:
	php main.php displayProducts

charts:
	for i in doc/*puml; do plantuml -tpng -p < $$i > doc/$$(basename $$i .puml).png; done 

lint:
	vendor/bin/phpcs -p --extensions=php --standard=PSR12 --error-severity=1 --warning-severity=0 -- src test

beautify:
	vendor/bin/phpcbf -p --extensions=php --standard=PSR12 --error-severity=1 --warning-severity=0 -- src test

schema:
	rm var/db.sqlite || true
	php -dauto_prepend_file=.meta/xdebug-trace-filter.php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql

select_product:
	sqlite var/db.sqlite 'select * from product'

desc_product:
	sqlite var/db.sqlite '.schema product'  | sed 's/(/(\n/; s/,/\n/g'


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
