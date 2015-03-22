.PHONY: test

test:
	php app/console cache:clear --env=test
	bin/phpunit -c app src

install:
	openssl genrsa -out app/var/jwt/private.pem -aes256 4096
	openssl rsa -pubout -in app/var/jwt/private.pem -out app/var/jwt/public.pem
	../composer.phar install -o
	php app/console doctrine:database:create
	../composer.phar update
	php app/console doctrine:schema:update --force
	HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d ' ' -f1`
	sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
	sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
	php app/console cache:clear --env=test
	php app/console cache:clear --env=prod
	bin/phpunit -c app src

chmod:
	HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d ' ' -f1`
	sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
	sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

cleandb:
	php app/console doctrine:schema:drop --force
	php app/console doctrine:schema:update --force
	yes y | php app/console doctrine:fixtures:load

deploy:
	git fetch --all
	git reset --hard origin/master
	../composer.phar self-update
	../composer.phar update
	php app/console doctrine:schema:update --force
	yes y | php app/console doctrine:fixtures:load
	php app/console cache:clear --env=test
	phpunit -c app
