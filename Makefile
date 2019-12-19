
charts:
	for i in doc/*puml; do plantuml -tpng -p < $$i > doc/$$(basename $$i .puml).png; done 

lint:
	vendor/bin/phpcs -p --extensions=php --standard=PSR2 --error-severity=1 --warning-severity=0 -- src test

schema:
	vendor/bin/doctrine orm:schema-tool:update --force --dump-sq
