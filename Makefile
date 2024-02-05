test:
	./vendor/bin/phpunit

phpstan:
	./vendor/bin/phpstan

fix:
	./vendor/bin/php-cs-fixer fix

all:
	make phpstan
	make fix
	make test
