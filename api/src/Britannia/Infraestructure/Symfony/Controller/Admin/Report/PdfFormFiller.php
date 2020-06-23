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
use Cocur\Slugify\Slugify;
use mikehaertl\pdftk\Pdf;
use Symfony\Component\Filesystem\Filesystem;

final class PdfFormFiller extends AbstractTemplateFillerOut
{

    public function create(TemplateBasedPdfReport $report, string $pathToTemplatesDir, string $pathToTempDir): string
    {
        $target = $this->getTarget($report, $pathToTempDir);
        $pathToTemplate = $this->getPathToTemplate($report, $pathToTemplatesDir);

        $pdf = new Pdf($pathToTemplate);
        $pdf->fillForm($report->params())
            ->needAppearances()
            ->saveAs($target);

        return $target;
    }


}
