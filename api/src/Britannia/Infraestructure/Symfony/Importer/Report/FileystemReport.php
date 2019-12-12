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

namespace Britannia\Infraestructure\Symfony\Importer\Report;


use Britannia\Infraestructure\Symfony\Importer\Resume;
use Symfony\Component\Filesystem\Filesystem;

abstract class FileystemReport extends ReportAbstract
{

    private $pathToReportDir;

    protected function __construct(string $pathToReportDir)
    {
        $this->pathToReportDir = $pathToReportDir;
    }

    public static function make(string $pathToReportDir): self
    {

        return new static($pathToReportDir);
    }

    public function success(Resume $resume): void
    {
    }

    /**
     * @param $line
     */
    protected function appendToFile(string $line, string $name): void
    {
        $fileName = sprintf('%s/%s', $this->pathToReportDir, $name);

        $line = sprintf("%s\n\n", trim($line));

        $filesystem = new Filesystem();

        $filesystem->appendToFile($fileName, $line);
        $filesystem->chmod($fileName, 0777);
    }

}
