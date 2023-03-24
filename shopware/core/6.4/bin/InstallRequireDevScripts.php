<?php

namespace Krme;

use Composer\Script\Event;

class InstallRequireDevScripts
{
    public static function installRequiredDevScriptsInShopwareRoot(Event $event): void {
        $composerRequires = self::getRequireDev($event);

        if (empty($composerRequires)) {
            return;
        }

        shell_exec(sprintf("cd ../../../ && composer require --dev %s", $composerRequires));
    }

    private static function getRequireDev(Event $event): string {
        $composerRequires = [];
        $devRequires = $event->getComposer()->getPackage()->getDevRequires();
        foreach ($devRequires as $devRequire) {
            if (!is_dir(dirname(__DIR__ . '/../../../../vendor/' . $devRequire->getTarget()))) {
                $composerRequires[] = $devRequire->getTarget() . ':' . $devRequire->getPrettyConstraint() ?? '*';
            }
        }

        return implode(' ', $composerRequires);
    }
}