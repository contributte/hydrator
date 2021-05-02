.PHONY: install qa cs csf phpstan tests coverage-clover coverage-html

install:
	composer update

qa:
	vendor/bin/linter src tests

phpstan:
	vendor/bin/phpstan analyse -l max src

tests:
	vendor/bin/codecept run
