<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Tests;


use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

trait DataProviderTrait
{
    public function dataFixtures(string $method)
    {
        $method = preg_replace('/^(test)(_*)(.*)$/', '$3', $method);

        $pathToDir = $this->pathToDir();
        $finder = $this->finder($pathToDir, $method);

        $data = [];
        foreach ($finder as $file) {
            $data[] = $this->loadDataFixturesFromFile($file);
        }

        return array_merge(...$data);
    }

    /**
     * @return string
     */
    private function pathToDir(): string
    {
        $className = str_replace(__NAMESPACE__, null, __CLASS__);
        $relativePathToDir = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $pathToDir = sprintf('%s/%s', __DIR__, $relativePathToDir);

        $this->makeDirectory($pathToDir);

        return $pathToDir;
    }

    /**
     * @param string $pathToDir
     */
    private function makeDirectory(string $pathToDir): void
    {
        $fileSystem = new Filesystem();
        $fileSystem->mkdir($pathToDir, 0777);
        $fileSystem->chown($pathToDir, 1000, true);
        $fileSystem->chgrp($pathToDir, 1000, true);
    }

    /**
     * @param string $pathToDir
     * @param string $method
     * @return Finder
     */
    private function finder(string $pathToDir, string $method): Finder
    {
        $finder = Finder::create()
            ->in($pathToDir)
            ->name("$method.*");

        if ($finder->count() == 0) {
            $this->touch($pathToDir, $method);
        }
        return $finder;
    }

    /**
     * @param string $pathToDir
     * @param string $method
     * @param Filesystem $fileSystem
     */
    private function touch(string $pathToDir, string $method): void
    {
        $pathToFile = sprintf('%s/%s.yaml', $pathToDir, $method);

        $fileSystem = new Filesystem();
        $fileSystem->appendToFile($pathToFile, '---');
        $fileSystem->chmod($pathToFile, 0666);
        $fileSystem->chown($pathToFile, 1000, true);
        $fileSystem->chgrp($pathToFile, 1000, true);
    }

    private function loadDataFixturesFromFile(SplFileInfo $fileInfo): array
    {
        $extension = $fileInfo->getExtension();
        if (in_array($extension, ['yaml', 'yml'])) {
            return $this->loadDataFixturesFromYaml($fileInfo);
        }

        return [];
    }

    private function loadDataFixturesFromYaml(SplFileInfo $fileInfo): array
    {
        return Yaml::parseFile($fileInfo->getPathname());
    }

}
