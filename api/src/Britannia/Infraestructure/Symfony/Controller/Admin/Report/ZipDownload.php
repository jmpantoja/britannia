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


use Britannia\Domain\Service\Report\Report;
use Britannia\Domain\Service\Report\ReportList;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use ZipArchive;

final class ZipDownload extends Download
{

    /**
     * @var PdfGenerator
     */
    private PdfGenerator $generator;

    public function __construct(PdfGenerator $generator, Environment $twig)
    {
        parent::__construct($twig);
        $this->generator = $generator;
    }

    public function createResponse(ReportList $reportList): Response
    {
        $pathToZipFile = $this->createTempZip($reportList);
        return $this->responseFromFile($pathToZipFile);
    }

    /**
     * @param iterable $fileNames
     * @param string $pathToZipFile
     * @return string
     */
    private function createTempZip(ReportList $reportList): string
    {
        $pathToZipFile = $this->pathToZipFile($reportList);

        $zip = new ZipArchive();
        $zip->open($pathToZipFile, ZipArchive::CREATE);

        collect($reportList)
            ->map($this->createTempPdf())
            ->each(fn(string $fileName) => $zip->addFile($fileName, basename($fileName)));

        return $pathToZipFile;
    }

    private function pathToZipFile(ReportList $reportList)
    {
        return sprintf('%s/%s.zip', ...[
            $this->generator->pathToTempDir(),
            $reportList->name()
        ]);
    }

    /**
     * @return \Closure
     */
    private function createTempPdf(): \Closure
    {
        return function (Report $report) {
            $output = $this->renderReport($report);
            return $this->generator->createTempFile(...[
                $output,
                $report->fileName(),
                $report->options()
            ]);
        };
    }

    /**
     * @param $html
     * @return Response
     */
    private function responseFromFile(string $pathToFile): Response
    {
        $headers = [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => sprintf('attachment; filename="%s"', basename($pathToFile)),
        ];

        return $this->createANewResponse($pathToFile, $headers);
    }
}
