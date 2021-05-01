.PHONY: install qa cs csf phpstan tests coverage-clover coverage-html

install:
	composer update

qa:
	vendor/bin/linter src tests

phpstan-install:
	mkdir -p temp/phpstan
	composer require -d temp/phpstan phpstan/phpstan:^0.10
	composer require -d temp/phpstan phpstan/phpstan-deprecation-rules:^0.10
	composer require -d temp/phpstan phpstan/phpstan-nette:^0.10
	composer require -d temp/phpstan phpstan/phpstan-strict-rules:^0.10

phpstan:
	temp/phpstan/vendor/bin/phpstan analyse -l max src

tests:
	vendor/bin/codecept run
