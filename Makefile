ICANBOOGIE_INSTANCE = dev
SERVER_PORT = 8010

vendor:
	@composer install

update: vendor
	@composer update

autoload: vendor
	@composer dump-autoload

optimize: vendor
	@composer dump-autoload -o
	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie optimize

unoptimize:
	@composer dump-autoload
	@rm -f vendor/icanboogie-combined.php
	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie clear cache

clean:
	@rm -Rf vendor

server:
	@cd web && \
	ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) \
	php -S localhost:$(SERVER_PORT) index.php
