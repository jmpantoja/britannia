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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Report;


use Britannia\Domain\Service\Report\TemplateBasedPdfReport;
use Britannia\Domain\Service\Report\TemplateBasedInteface;
use Cocur\Slugify\Slugify;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractTemplateFillerOut
{
    /**
     * @param TemplateBasedInteface $report
     * @param string $pathToTempDir
     * @return string
     */
    protected function getTarget(TemplateBasedInteface $report, string $pathToTempDir): string
    {
        //$fileName = Slugify::create()->slugify($report->title());

        $fileName = ClassnameToTemplate::make($report)
            ->target();

        $target = sprintf('%s/%s', $pathToTempDir, $fileName);

        $fileSystem = new Filesystem();
        $fileSystem->mkdir(dirname($target));

        return $target;
    }

    /**
     * @param TemplateBasedInteface $report
     * @param string $pathToTemplatesDir
     * @return string
     */
    protected function getPathToTemplate(TemplateBasedInteface $report, string $pathToTemplatesDir): string
    {
        $template = ClassnameToTemplate::make($report)
            ->main();

        return sprintf('%s/%s', $pathToTemplatesDir, $template);
    }
}
