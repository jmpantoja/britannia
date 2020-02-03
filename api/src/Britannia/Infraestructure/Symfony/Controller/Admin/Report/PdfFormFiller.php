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


use Britannia\Domain\Service\Report\FormBasedPdfInteface;
use Cocur\Slugify\Slugify;
use mikehaertl\pdftk\Pdf;
use Symfony\Component\Filesystem\Filesystem;

final class PdfFormFiller
{

    public function create(FormBasedPdfInteface $report, string $pathToTemplatesDir, string $pathToTempDir): string
    {
        $target = $this->getTarget($report, $pathToTempDir);
        $pathToTemplate = $this->getPathToTemplate($report, $pathToTemplatesDir);

        $pdf = new Pdf($pathToTemplate);
        $pdf->fillForm($report->params())
            ->needAppearances()
            ->saveAs($target);

        return $target;
    }

    /**
     * @param FormBasedPdfInteface $report
     * @param string $pathToTempDir
     * @return string
     */
    private function getTarget(FormBasedPdfInteface $report, string $pathToTempDir): string
    {
        $fileName = Slugify::create()->slugify($report->title());
        $target = sprintf('%s/%s.pdf', $pathToTempDir, $fileName);

        $fileSystem = new Filesystem();
        $fileSystem->mkdir(dirname($target));

        return $target;
    }

    /**
     * @param FormBasedPdfInteface $report
     * @param string $pathToTemplatesDir
     * @return string
     */
    private function getPathToTemplate(FormBasedPdfInteface $report, string $pathToTemplatesDir): string
    {
        $template = ClassnameToTemplate::make($report)
            ->filter();

        return sprintf('%s/%s', $pathToTemplatesDir, $template);
    }
}
