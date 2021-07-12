# server
ICANBOOGIE_INSTANCE=dev
SERVER_PORT=8010

# deployment
TARGET=com.olvlvl.www
TARGET_TMP=$(TARGET)_tmp
ARCHIVE=$(TARGET).tar.gz
ARCHIVE_PATH=/tmp/$(ARCHIVE)
USER=$(COM_OLVLVL_WWW_USER)
SERVER=$(COM_OLVLVL_WWW_SERVER)
HOST=$(USER)@$(SERVER)

vendor:
	@composer install

update: vendor
	@composer update

autoload: vendor
	@composer dump-autoload

optimize: vendor
	@composer dump-autoload -oa
	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie optimize
	@php vendor/icanboogie-combined.php

unoptimize: vendor
	@composer dump-autoload
	@rm -f vendor/icanboogie-combined.php
	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie clear cache

reset:
	@rm -Rf vendor

clear-cache:
	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie clear cache
	rm -f repository/db.sqlite

server:
	@rm -rf repository/cache/*
	@rm -rf repository/var/*
	@rm -f repository/db.sqlite
	@echo "Open http://localhost:$(SERVER_PORT) when ready."
	@docker-compose up

php-server:
	@cd web && \
	ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) \
	php -S localhost:$(SERVER_PORT) index.php

deploy: vendor optimize clear-cache
	rm -f $(ARCHIVE_PATH)
	tar -cjSf $(ARCHIVE_PATH) --exclude .git --exclude .idea --exclude tests --exclude .DS_Store --exclude ._.DS_Store .
	scp $(ARCHIVE_PATH) $(HOST):$(ARCHIVE)
	ssh $(HOST) rm -Rf $(TARGET_TMP)
	ssh $(HOST) mkdir -p $(TARGET_TMP)
	ssh $(HOST) tar -xf $(ARCHIVE) -C $(TARGET_TMP)
	ssh $(HOST) rm -Rf $(TARGET)
	ssh $(HOST) mv $(TARGET_TMP) $(TARGET)
	ssh $(HOST) rm $(ARCHIVE)

ssh:
	ssh $(HOST)

.PHONY: update autoload optimize unoptimize reset clear-cache server ssh deploy
