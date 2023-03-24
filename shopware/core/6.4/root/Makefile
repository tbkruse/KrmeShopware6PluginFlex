.DEFAULT_GOAL := help

## Variable definition
BASE_URL?=http://localhost
PLUGIN_ROOT=$(realpath .)
PROJECT_ROOT=$(realpath ../../../)

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
.PHONY: help

administration-fix: ## Run eslint on the administration files
	if [ ! -d $(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration/node_modules/ ]; then \
		npm install --prefix $(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration; \
	fi
	$(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration/node_modules/.bin/eslint --ignore-path .eslintignore --rule 'sw-deprecation-rules/private-feature-declarations: off' --config $(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration/.eslintrc.js --ext .js,.vue --fix src/Resources/app/administration
.PHONY: administration-fix

storefront-fix: ## Run eslint on the storefront files
	if [ ! -d $(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront/node_modules/ ]; then \
		npm install --prefix $(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront; \
	fi
	$(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/eslint --ignore-path .eslintignore --rule 'sw-deprecation-rules/private-feature-declarations: off' --config $(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront/.eslintrc.js --ext .js,.vue --fix src/Resources/app/storefront
.PHONY: storefront-fix

administration-lint: ## Run eslint on the administration files
	if [ ! -d $(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration/node_modules/ ]; then \
		npm install --prefix $(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration; \
	fi
	$(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration/node_modules/.bin/eslint --ignore-path .eslintignore --rule 'sw-deprecation-rules/private-feature-declarations: off' --config $(PROJECT_ROOT)/vendor/shopware/administration/Resources/app/administration/.eslintrc.js --ext .js,.vue src/Resources/app/administration
.PHONY: administration-lint

storefront-lint: ## Run eslint on the storefront files
	if [ ! -d $(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront/node_modules/ ]; then \
		npm install --prefix $(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront; \
	fi
	$(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/eslint --ignore-path .eslintignore --rule 'sw-deprecation-rules/private-feature-declarations: off' --config $(PROJECT_ROOT)/vendor/shopware/storefront/Resources/app/storefront/.eslintrc.js --ext .js,.vue src/Resources/app/storefront
.PHONY: storefront-lint

cs-lint: ## Run cs lint
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php -c=true; \
        echo 'c' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 'c' ]; then \
        php vendor-builder.php -c=true; \
        echo 'c' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	echo "Lint php files";
	php $(PLUGIN_ROOT)/vendor/vendor/bin/ecs check --config=easy-coding-standard.php src;
.PHONY: cs-lint

cs-fix: ## Run cs fix
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php -c=true; \
        echo 'c' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 'c' ]; then \
        php vendor-builder.php -c=true; \
        echo 'c' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	echo "Fix php files";
	php $(PLUGIN_ROOT)/vendor/vendor/bin/ecs check --config=easy-coding-standard.php src --fix;
.PHONY: cs-fix

phpunit: ## Run phpunit
	if [ ! -f $(PROJECT_ROOT)/var/test/jwt ]; then \
		mkdir -p $(PROJECT_ROOT)/var/test/jwt; \
		cp -p $(PROJECT_ROOT)/config/jwt/private.pem $(PROJECT_ROOT)/var/test/jwt/private.pem; \
		cp -p $(PROJECT_ROOT)/config/jwt/public.pem $(PROJECT_ROOT)/var/test/jwt/public.pem; \
	fi
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php; \
        echo 'a' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 'a' ]; then \
        php vendor-builder.php; \
        echo 'a' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	SYMFONY_DEPRECATIONS_HELPER=disabled $(PROJECT_ROOT)/vendor/bin/phpunit
.PHONY: phpunit

phpunit-coverage: ## Run phpunit coverage
	cd $(PROJECT_ROOT)/../ && make xdebug-on && cd $(PLUGIN_ROOT);
	if [ ! -f $(PROJECT_ROOT)/var/test/jwt ]; then \
		mkdir -p $(PROJECT_ROOT)/var/test/jwt; \
		cp -p $(PROJECT_ROOT)/config/jwt/private.pem $(PROJECT_ROOT)/var/test/jwt/private.pem; \
		cp -p $(PROJECT_ROOT)/config/jwt/public.pem $(PROJECT_ROOT)/var/test/jwt/public.pem; \
	fi
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php; \
        echo 'a' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 'a' ]; then \
        php vendor-builder.php; \
        echo 'a' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	SYMFONY_DEPRECATIONS_HELPER=disabled XDEBUG_MODE=coverage $(PROJECT_ROOT)/vendor/bin/phpunit --coverage-html coverage
.PHONY: phpunit-coverage

run-rector: shopware-cli-prepare## Run rector
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php -d=true; \
        echo 'd' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 'd' ]; then \
        php vendor-builder.php -d=true; \
        echo 'd' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	php $(PLUGIN_ROOT)/bin/rector-config-generator.php
	$(PLUGIN_ROOT)/vendor/vendor/bin/rector process
.PHONY: run-rector

run-rector-dry: ## Run rector
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php -d=true; \
        echo 'd' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 'd' ]; then \
        php vendor-builder.php -d=true; \
        echo 'd' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	php $(PLUGIN_ROOT)/bin/rector-config-generator.php
	$(PLUGIN_ROOT)/vendor/vendor/bin/rector process --dry-run
.PHONY: run-rector-dry

open-cypress: ## Open cypress
	if [ ! -d $(PLUGIN_ROOT)/tests/e2e/node_modules/ ]; then \
		npm install --prefix $(PLUGIN_ROOT)/tests/e2e; \
	fi
	APP_URL=http://localhost $(PLUGIN_ROOT)/tests/e2e/node_modules/.bin/cypress open --project tests/e2e --env projectRoot=tests/e2e
.PHONY: open-cypress

run-cypress: ## Run cypress
	if [ ! -d $(PLUGIN_ROOT)/tests/e2e/node_modules/ ]; then \
		npm install --prefix $(PLUGIN_ROOT)/tests/e2e; \
	fi
	APP_URL=http://localhost $(PLUGIN_ROOT)/tests/e2e/node_modules/.bin/cypress run --project tests/e2e --browser=electron --env projectRoot=tests/e2e
.PHONY: run-cypress

run-phpstan: ## Run php stan
	if [ ! -f $(PLUGIN_ROOT)/vendor-builder.lock ]; then \
        php vendor-builder.php -p=true; \
        echo 't' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	builderLock=`cat $(PLUGIN_ROOT)/vendor-builder.lock`; \
	if [ ! $$builderLock = 't' ]; then \
        php vendor-builder.php -p=true; \
        echo 't' > $(PLUGIN_ROOT)/vendor-builder.lock; \
    fi
	php $(PLUGIN_ROOT)/bin/phpstan-config-generator.php
	php $(PLUGIN_ROOT)/vendor/vendor/bin/phpstan analyze --configuration $(PLUGIN_ROOT)/phpstan.neon --autoload-file=$(PLUGIN_ROOT)/vendor/vendor/autoload.php src
.PHONY: run-phpstan

remove-lock-file: ## Remove vendor builder lock file
	rm -f $(PLUGIN_ROOT)/vendor-builder.lock
.PHONY: remove-lock-file

development-env: dev disable-http-cache ## Run development-env
.PHONY: development-env

dev: ## Run dev
	sed -i 's/APP_ENV=\"prod\"/APP_ENV="dev"/g' $(PROJECT_ROOT)/.env
	cd $(PROJECT_ROOT) && rm -rf var/cache/*
	cd $(PROJECT_ROOT) && rm -rf var/log/*
	cd $(PROJECT_ROOT) && php bin/console cache:clear
	cd $(PROJECT_ROOT) && php bin/console cache:warmup
.PHONY: dev

disable-http-cache: ## Run disable-http-cache
	sed -i 's/SHOPWARE_HTTP_CACHE_ENABLED=\"1\"/SHOPWARE_HTTP_CACHE_ENABLED="0"/g' $(PROJECT_ROOT)/.env
	cd $(PROJECT_ROOT) && rm -rf var/cache/*
	cd $(PROJECT_ROOT) && rm -rf var/log/*
	cd $(PROJECT_ROOT) && php bin/console cache:clear
	cd $(PROJECT_ROOT) && php bin/console cache:warmup
.PHONY: disable-http-cache

prod-environment: ## Run prod-environment
	sed -i 's/APP_ENV=\"dev\"/APP_ENV="prod"/g' $(PROJECT_ROOT)/.env
	sed -i 's/SHOPWARE_HTTP_CACHE_ENABLED=\"0\"/SHOPWARE_HTTP_CACHE_ENABLED="1"/g' $(PROJECT_ROOT)/.env
	cd $(PROJECT_ROOT) && rm -rf var/cache/*
	cd $(PROJECT_ROOT) && rm -rf var/log/*
	cd $(PROJECT_ROOT) && php bin/console cache:clear
	cd $(PROJECT_ROOT) && php bin/console cache:warmup
.PHONY: prod-environment

install-sqlite: ## Run install-sqlite
	sudo apt update
	sudo apt install php7.4-sqlite
	sudo phpenmod -v 7.4 pdo_sqlite
.PHONY: install-sqlite

shopware-cli-install: ## Zip Project
ifneq (, $(which shopware-cli))
	curl -1sLf 'https://dl.cloudsmith.io/public/friendsofshopware/stable/setup.deb.sh' | sudo -E bash
	sudo apt install shopware-cli
endif
.PHONY: shopware-cli-install


shopware-cli-zip: shopware-cli-install ## Zip Project
	cd ~ && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension zip $(PLUGIN_ROOT) --disable-git --release
.PHONY: shopware-cli-zip


shopware-cli-build: shopware-cli-install ## Build Project
	cd ~ && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension build $(PLUGIN_ROOT)
	cd $(PLUGIN_ROOT) && php vendor-builder.php
	cd /var/www/html && bin/console assets:install
	cd /var/www/html && bin/console cache:clear
.PHONY: shopware-cli-build

shopware-cli-prepare: shopware-cli-install ## Watch Project
	SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension prepare $(PLUGIN_ROOT)
.PHONY: shopware-cli-prepare

shopware-cli-validate: shopware-cli-install ## Watch Project
	SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension validate $(PLUGIN_ROOT)
.PHONY: shopware-cli-validate

shopware-cli-watch: shopware-cli-install ## Watch Project
	cd ~ && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension admin-watch $(PLUGIN_ROOT) http://localhost
.PHONY: shopware-cli-watch

prepare-push: administration-fix cs-fix run-phpstan run-rector shopware-cli-prepare shopware-cli-build ## Run prepare push
.PHONY: prepare-push

