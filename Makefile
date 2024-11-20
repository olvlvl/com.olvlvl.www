# Pre-requisite
# brew install gnu-tar
# npm -g i clean-css-cli
# npm install uglify-js -g

# server
LOCAL_PORT=8010

# deployment
TARGET=com.olvlvl.www
TARGET_TMP=$(TARGET)_tmp
ARCHIVE=$(TARGET).tar.gz
ARCHIVE_PATH=/tmp/$(ARCHIVE)
USER=$(COM_OLVLVL_WWW_USER)
SERVER=$(COM_OLVLVL_WWW_HOST)
HOST=$(USER)@$(SERVER)

all: vendor assets

vendor:
	@composer install

#
# App
#

.PHONY: reset
reset:
	@echo "Reset installation"
	@rm -f build/xdebug/*
	@rm -rf var/cache/*
	@rm -f var/db.sqlite
	@rm -f vendor/icanboogie-combined.php
	@docker compose run --rm app ./icanboogie cache:clear

.PHONY: for_dev
for_dev: vendor reset
	@echo "Preparing for development"
	@docker compose run --rm app composer install
	@docker compose run --rm app composer dump-autoload

.PHONY: for_prod
for_prod: vendor reset
	@echo "Preparing for production"
	@docker compose run --rm app composer install --no-dev
	@docker compose run --rm app composer dump-autoload -oa
#	@docker compose run --rm app ./icanboogie optimize
#	@php vendor/icanboogie-combined.php

.PHONY: server
server: for_dev
	@echo "Open http://localhost:$(LOCAL_PORT) when ready."
	@docker compose up app

.PHONY: server-staging
server-staging: for_prod
	@echo "Open http://localhost:$(LOCAL_PORT) when ready."
	@docker compose up app-staging

.PHONY: shell
shell:
	@docker compose exec app bash

.PHONY: lint
lint:
	@XDEBUG_MODE=off vendor/bin/phpstan

#
# Deployment
#

.PHONY: deploy
deploy: for_prod
	@docker compose run --rm app ./icanboogie articles:sync
	# We're using GNU tar here: `brew install gnu-tar`
	rm -f $(ARCHIVE_PATH)
	COPYFILE_DISABLE=1 gtar -cjSf $(ARCHIVE_PATH) \
		--exclude-vcs \
		--exclude .DS_Store \
		--exclude .idea \
		--exclude app/dev \
		--exclude app/staging \
		--exclude composer.lock \
		--exclude content/articles-archive \
		--exclude content/articles-backlog \
		.
	scp $(ARCHIVE_PATH) $(HOST):$(ARCHIVE)
	ssh $(HOST) "\
		set -eux && \
		rm -Rf $(TARGET_TMP) && \
		mkdir -p $(TARGET_TMP) && \
		tar -xf $(ARCHIVE) -C $(TARGET_TMP) && \
		mv $(TARGET) $(TARGET)_rm && \
		mv $(TARGET_TMP) $(TARGET) && \
		rm -Rf $(TARGET)_rm && \
		rm $(ARCHIVE)"

.PHONY: ssh
ssh:
	ssh -t $(HOST) "cd $(TARGET); bash --login"

#
# Assets
#

.PHONY: assets
assets: web/assets/page.js web/assets/page.css

web/assets/page.js: assets/text-balancer.js assets/prism.js assets/page.js
	cat $^ | uglifyjs --compress --mangle > $@

web/assets/page.css: assets/prism.css assets/page.css
	cat $^ | cleancss > $@
