import:
	php -dauto_prepend_file=.meta/xdebug-trace-filter.php main.php importProducts || true
	@echo
	.meta/find-latest /tmp/xdebug | grep --colour=auto trace | tail -n1 | xargs sed -f .meta/strip-trace-paths.sed

all:
	php main.php


display:
	php main.php displayProducts

charts:
	for i in doc/*puml; do plantuml -tpng -p < $$i > doc/$$(basename $$i .puml).png; done 

lint:
	vendor/bin/phpcs -p --extensions=php --standard=PSR2 --error-severity=1 --warning-severity=0 -- src test

schema:
	vendor/bin/doctrine orm:schema-tool:update --force --dump-sq


unit:
	bash .meta/run-it.sh php -dauto_prepend_file=.meta/xdebug-trace-filter.php ./vendor/bin/phpunit \
		--stop-on-error --stop-on-failure \
		--testsuite=unit
