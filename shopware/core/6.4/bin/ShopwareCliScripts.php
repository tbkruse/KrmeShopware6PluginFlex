<?php

namespace Krme;

use Composer\Script\Event;

class ShopwareCliScripts
{
    public static function build(Event $event): void
    {
        self::installCli();
        $pluginRoot = self::getPluginRoot();

        shell_exec(sprintf("cd ~ && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension build %s", $pluginRoot));
        shell_exec(sprintf("cd %s && composer install --no-dev --no-scripts", $pluginRoot));
        shell_exec('cd /var/www/html && bin/console assets:install');
        shell_exec('cd /var/www/html && bin/console cache:clear');
    }

    public static function prepare(Event $event): void {
        self::installCli();
        $pluginRoot = self::getPluginRoot();

        shell_exec('SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension prepare ' . $pluginRoot);
    }

    public static function validate(Event $event): void {
        self::installCli();
        $pluginRoot = self::getPluginRoot();

        shell_exec('SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension validate ' . $pluginRoot);
    }

    public static function zip(Event $event): void {
        self::installCli();
        $pluginRoot = self::getPluginRoot();

        shell_exec(sprintf("SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension zip %s --disable-git --release", $pluginRoot));
    }

    public static function watch(Event $event): void {
        self::installCli();
        $pluginRoot = self::getPluginRoot();

        shell_exec(sprintf("cd /var/www/html && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli extension admin-watch %s http://localhost", $pluginRoot));
    }

    public static function adminBuild(Event $event): void {
        self::installCli();

        shell_exec('cd /var/www/html && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli project admin-build');
    }

    public static function storefrontBuild(Event $event): void {
        self::installCli();

        shell_exec('cd /var/www/html && SHOPWARE_PROJECT_ROOT=/var/www/html shopware-cli project storefront-build');
    }

    private static function installCli(): void
    {
        if (PHP_OS_FAMILY !== 'Linux') {
            return;
        }

        if (null === shell_exec("command -v shopware-cli")) {
            shell_exec("curl -1sLf 'https://dl.cloudsmith.io/public/friendsofshopware/stable/setup.deb.sh' | sudo -E bash' && sudo apt install shopware-cli");
        }
    }

    private static function getPluginRoot(): string {
        return  __DIR__ . '/..';
    }
}