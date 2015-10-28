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
	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie

server:
	@cd web && \
	ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) \
	php -S localhost:$(SERVER_PORT) index.php
