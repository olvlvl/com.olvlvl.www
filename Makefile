# Pre-requisite
# brew install gnu-tar
# npm -g i clean-css-cli
# npm install uglify-js -g

# server
ICANBOOGIE_INSTANCE=dev
ICANBOOGIE_CMD=ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) vendor/bin/icanboogie
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

.PHONY: optimize
optimize: vendor clear-cache
	@composer dump-autoload -oa
#	@ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) icanboogie optimize
#	@php vendor/icanboogie-combined.php

.PHONY: unoptimize
unoptimize: vendor
	@composer dump-autoload
	@rm -f vendor/icanboogie-combined.php
	$(ICANBOOGIE_CMD) cache:clear

.PHONY: clear-cache
clear-cache:
	$(ICANBOOGIE_CMD) cache:clear
	rm -f repository/db.sqlite

.PHONY: server
server:
	@rm -rf repository/cache/*
	@rm -rf repository/var/*
	@rm -f repository/db.sqlite
	@echo "Open http://localhost:$(LOCAL_PORT) when ready."
	@docker-compose up

.PHONY: php-server
php-server: clear-cache
	@cd web && \
	ICANBOOGIE_INSTANCE=$(ICANBOOGIE_INSTANCE) \
	php -S localhost:$(LOCAL_PORT) index.php

.PHONY: php-server-staging
php-server-staging: optimize
	@cd web && \
	ICANBOOGIE_INSTANCE=staging \
	php -S localhost:$(LOCAL_PORT) index.php

.PHONY: deploy
deploy: vendor optimize clear-cache
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
		--exclude content/articles-dev \
		.
	ll $(ARCHIVE_PATH)
	#tar -tf $(ARCHIVE_PATH) | sort
#	scp $(ARCHIVE_PATH) $(HOST):$(ARCHIVE)
#	ssh $(HOST) "\
#		set -eux && \
#		rm -Rf $(TARGET_TMP) && \
#		mkdir -p $(TARGET_TMP) && \
#		tar -xf $(ARCHIVE) -C $(TARGET_TMP) && \
#		mv $(TARGET) $(TARGET)_rm && \
#		mv $(TARGET_TMP) $(TARGET) && \
#		rm -Rf $(TARGET)_rm && \
#		rm $(ARCHIVE)"

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
