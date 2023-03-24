<?php

namespace Krme;

class EslintScripts
{
    public static function administrationFix(): void {
        if (!is_dir(dirname(__DIR__ . '/../src/Resources/app/administration'))) {
            return;
        }

        self::installAdminNodeModules();
        $shopwareRoot = self::getShopwareRoot();

        shell_exec(sprintf("%svendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/eslint --ignore-path .eslintignore --rule 'sw-deprecation-rules/private-feature-declarations: off' --config %svendor/shopware/administration/Resources/app/administration/.eslintrc.js --ext .js,.vue --fix src/Resources/app/administration", $shopwareRoot, $shopwareRoot));
    }
    public static function administrationLint(): void {
        if (!is_dir(dirname(__DIR__ . '/../src/Resources/app/administration'))) {
            return;
        }

        self::installAdminNodeModules();
        $shopwareRoot = self::getShopwareRoot();

        shell_exec(sprintf("%svendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/eslint --ignore-path .eslintignore --rule 'sw-deprecation-rules/private-feature-declarations: off' --config %svendor/shopware/administration/Resources/app/administration/.eslintrc.js --ext .js,.vue src/Resources/app/administration", $shopwareRoot, $shopwareRoot));
    }

    public static function storefrontFix(): void {
        if (!is_dir(dirname(__DIR__ . '/../src/Resources/app/storefront'))) {
            return;
        }

        self::installStorefrontNodeModules();
        $shopwareRoot = self::getShopwareRoot();

        shell_exec(sprintf("%svendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/eslint --ignore-path .eslintignore --config %svendor/shopware/storefront/Resources/app/storefront/.eslintrc.js --ext .js,.vue src/Resources/app/storefront", $shopwareRoot, $shopwareRoot));
    }
    public static function storefrontLint(): void {
        if (!is_dir(dirname(__DIR__ . '/../src/Resources/app/storefront'))) {
            return;
        }

        self::installStorefrontNodeModules();
        $shopwareRoot = self::getShopwareRoot();

        shell_exec(sprintf("%svendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/eslint --ignore-path .eslintignore --config %svendor/shopware/storefront/Resources/app/storefront/.eslintrc.js --ext .js,.vue --fix src/Resources/app/storefront", $shopwareRoot, $shopwareRoot));
    }


    public static function stylelintFix(): void {
        if (!is_dir(dirname(__DIR__ . '/../src/Resources/app/storefront'))) {
            return;
        }

        self::installStorefrontNodeModules();
        $shopwareRoot = self::getShopwareRoot();

        shell_exec(sprintf("%svendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/stylelint \"./src/Resources/app/storefront/**/*.scss\" --syntax scss --config ./.stylelintrc", $shopwareRoot));
    }
    public static function stylelintLint(): void {
        if (!is_dir(dirname(__DIR__ . '/../src/Resources/app/storefront'))) {
            return;
        }

        self::installStorefrontNodeModules();
        $shopwareRoot = self::getShopwareRoot();

        shell_exec(sprintf("%svendor/shopware/storefront/Resources/app/storefront/node_modules/.bin/stylelint \"./src/Resources/app/storefront/**/*.scss\" --fix --syntax scss --config ./.stylelintrc", $shopwareRoot));
    }

    private static function installStorefrontNodeModules(): void {
        $shopwareRoot = self::getShopwareRoot();

        if (is_dir(dirname($shopwareRoot . 'vendor/shopware/storefront/Resources/app/storefront/node_modules'))) {
            return;
        }

        shell_exec(sprintf("npm i --prefix %svendor/shopware/storefront/Resources/app/storefront", $shopwareRoot));
    }
    private static function installAdminNodeModules(): void {
        $shopwareRoot = self::getShopwareRoot();

        if (is_dir(dirname($shopwareRoot . 'vendor/shopware/administration/Resources/app/administration/node_modules'))) {
            return;
        }

        shell_exec(sprintf("npm i --prefix %svendor/shopware/administration/Resources/app/administration", $shopwareRoot));
    }
    private static function getShopwareRoot(): string {
        return __DIR__ . '/../../../../';
    }
}