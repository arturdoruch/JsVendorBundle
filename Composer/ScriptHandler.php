<?php

namespace ArturDoruch\JsVendorBundle\Composer;

use Composer\Package\Package;
use Composer\Package\Version\VersionParser;
use Composer\Script\Event;
use Composer\Util\Filesystem;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class ScriptHandler
{
    /**
     * Downloads js vendors from JsVendor git repository.
     * @link https://github.com/arturdoruch/JsVendor
     *
     * @param Event $event
     */
    public static function installJsVendor(Event $event)
    {
        $composer = $event->getComposer();

        $version = '1.1.0';
        $targetDir = __DIR__ . '/../Resources/public/js';

        if ($version === self::getInstalledVersion($targetDir)) {
            //$event->getIO()->write('The JavaScript files from package arturdoruch/js-vendor v' . $version . ' are already installed.');
            return;
        }

        $versionParser = new VersionParser();
        $normalizedVersion = $versionParser->normalize($version);

        $package = new Package('arturdoruch/js-vendor', $normalizedVersion, $version);
        $package->setTargetDir($targetDir);
        $package->setInstallationSource('dist');
        $package->setSourceType('git');
        $package->setSourceReference($version);
        $package->setSourceUrl('https://github.com/arturdoruch/JsVendor');

        // Download the JsVendorBundle
        $downloader = $composer->getDownloadManager()->getDownloader('git');
        $downloader->download($package, $targetDir);

        self::writePackageVersion($version, $targetDir);

        // Remove unwanted files
        $files = array(
            $targetDir . '/.git',
            $targetDir . '/.gitignore',
            $targetDir . '/composer.json'
        );

        $fs = new Filesystem();

        foreach ($files as $file) {
            $fs->remove($file);
        }
    }

    /**
     * @param string $targetDir
     *
     * @return null|string
     */
    private static function getInstalledVersion($targetDir)
    {
        if (!file_exists($filename = $targetDir . '/version.txt')) {
            return null;
        }

        return trim(file_get_contents($filename));
    }

    /**
     * Writes version of downloaded arturdoruch/js-vendor package into txt file.
     *
     * @param string $targetDir
     * @param string $version
     */
    private static function writePackageVersion($version, $targetDir)
    {
        file_put_contents($targetDir . '/version.txt', $version);
    }
}
