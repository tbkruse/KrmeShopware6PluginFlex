<?php declare(strict_types=1);
/*
 * (c) KruseMedien GmbH <info@krusemedien.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Shopware\Core\Framework\Plugin\KernelPluginLoader\StaticKernelPluginLoader;
use Shopware\Core\Kernel;
use Symfony\Component\Dotenv\Dotenv;

$classLoader = require __DIR__ . '/../../../../vendor/autoload.php';
(new Dotenv(true))->load(__DIR__ . '/../../../../.env');

$shopwareVersion = \Composer\InstalledVersions::getVersion('shopware/core');

$pluginRootPath = dirname(__DIR__);
$pluginLoader = new StaticKernelPluginLoader($classLoader, null, []);

$kernel = new Kernel('dev', true, $pluginLoader, $shopwareVersion);
$kernel->boot();
$projectDir = $kernel->getProjectDir();
$cacheDir = $kernel->getCacheDir();

$relativeCacheDir = str_replace($projectDir, '', $cacheDir);

$rectorConfigDist = file_get_contents(__DIR__ . '/../rector.php.dist');
if ($rectorConfigDist === false) {
    throw new RuntimeException('rector.yaml.dist file not found');
}

// because the cache dir is hashed by Shopware, we need to set the PHPStan config dynamically
$rectorConfig = str_replace(
    [
        "\n        # the placeholder \"%ShopwareHashedCacheDir%\" will be replaced on execution by bin/rector-config-generator.php script",
        '%ShopwareHashedCacheDir%',
    ],
    [
        '',
        $relativeCacheDir,
    ],
    $rectorConfigDist
);

file_put_contents(__DIR__ . '/../rector.php', $rectorConfig);
