<?php

namespace ArturDoruch\JsVendorBundle\Composer;

use Composer\Composer;
use Composer\Package\Package;
use Composer\Package\Version\VersionParser;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

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

        $targetDir = __DIR__ . '/../Resources/public/js';

        $version = self::getVersion($composer);
        $versionParser = new VersionParser();
        $normalizedVersion = $versionParser->normalize($version);

        $package = new Package('arturdoruch/js-vendor', $normalizedVersion, $version);
        $package->setTargetDir($targetDir);
        $package->setInstallationSource('dist');
        $package->setSourceType('git');
        $package->setSourceReference($version);
        $package->setSourceUrl('https://github.com/arturdoruch/JsVendor');

        // Download the JsBundle
        $downloader = $composer->getDownloadManager()->getDownloader('git');
        $downloader->download($package, $targetDir);

        // Remove unwanted files
        $fs = new Filesystem();
        $fs->remove(array(
                $targetDir . '/.git',
                $targetDir . '/.gitignore',
                $targetDir . '/composer.json'
            ));
    }

    /**
     * Gets JsBundle version.
     *
     * @param Composer $composer
     * @return string
     */
    private static function getVersion(Composer $composer)
    {
        $packages = $composer->getRepositoryManager()->getLocalRepository()->findPackages('arturdoruch/js-vendor-bundle');

        if (!$package = array_shift($packages)) {
            throw new \RuntimeException('The "js-vendor-bundle" package version could not be detected.');
        }

        $versionPaths = explode('.', $package->getPrettyVersion());
        $versionPaths[0] = $versionPaths[0] - 1;

        return implode('.', $versionPaths);
    }

}
