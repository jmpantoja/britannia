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


use Britannia\Domain\Service\Report\HtmlBasedPdfReport;
use Britannia\Domain\Service\Report\ReportInterface;
use Britannia\Domain\Service\Report\ReportList;
use Britannia\Domain\Service\Report\TemplateBasedXlsxReport;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

final class FileDownload
{
    /**
     * @var PdfGenerator
     */
    private PdfGenerator $pdfGenerator;

    /**
     * @var PdfFormFiller
     */
    private PdfFormFiller $formFiller;


    /**
     * @var string
     */
    private string $pathToTempDir;
    private string  $pathToTemplatesDir;
    /**
     * @var XlsxGenerator
     */
    private XlsxGenerator $xlsxGenerator;

    public function __construct(XlsxGenerator $xlsxGenerator,
                                PdfGenerator $pdfGenerator,
                                PdfFormFiller $formFiller,
                                ParameterBagInterface $parameterBag)
    {
        $this->xlsxGenerator = $xlsxGenerator;
        $this->pdfGenerator = $pdfGenerator;
        $this->formFiller = $formFiller;

        $this->pathToTemplatesDir = $parameterBag->get('twig.default_path');

        $pathToLogDir = $parameterBag->get('kernel.logs_dir');
        $this->setTempDir($pathToLogDir);

    }

    private function setTempDir(string $pathToLogDir): self
    {
        $format = '%s/tmp/%s';
        $this->pathToTempDir = sprintf($format, ...[
            dirname($pathToLogDir),
            uniqid('reports-')
        ]);
        return $this;
    }

    /**
     * @return string
     */
    private function pathToTempDir(): string
    {
        return $this->pathToTempDir;
    }

    private function pathToTemplatesDir(): string
    {
        return $this->pathToTemplatesDir;
    }

    public function createResponse(ReportList $reportList): Response
    {
        $files = $this->generateTempFiles($reportList);

        if (empty($files)) {
            return new Response('No hay nada que generar', 404);
        }

        $pathToFile = $files[0];
        if (count($files) > 1) {
            $pathToFile = $this->packFilesIntoZip($reportList->name(), $files);
        }

        return $this->createResponseFromFilePath($pathToFile);
    }

    /**
     * @param ReportList $reportList
     * @return array
     */
    public function generateTempFiles(ReportList $reportList): array
    {
        $files = [];
        foreach ($reportList as $report) {
            $files[] = $this->generateTempPdfFile($report);
        }
        return $files;
    }

    private function generateTempPdfFile(ReportInterface $report): string
    {
        if ($report instanceof TemplateBasedXlsxReport) {
            return $this->xlsxGenerator->create($report, $this->pathToTemplatesDir(), $this->pathToTempDir());
        }

        if ($report instanceof HtmlBasedPdfReport) {
            return $this->pdfGenerator->create($report, $this->pathToTempDir());
        }

        return $this->formFiller->create($report, $this->pathToTemplatesDir(), $this->pathToTempDir());
    }

    private function packFilesIntoZip(string $courseName, array $files)
    {
        $pathToZipFile = sprintf('%s/%s.zip', ...[
            $this->pathToTempDir(),
            $courseName
        ]);

        $zip = new ZipArchive();
        $zip->open($pathToZipFile, ZipArchive::CREATE);

        foreach ($files as $pathToFile) {
            $zip->addFile($pathToFile, basename($pathToFile));
        }

        return $pathToZipFile;
    }


    private function createResponseFromFilePath(string $pathToFile)
    {
        return (new BinaryFileResponse($pathToFile))
            ->setContentDisposition('attachment');
    }


}
