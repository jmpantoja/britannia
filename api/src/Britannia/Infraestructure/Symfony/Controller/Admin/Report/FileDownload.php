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


use Britannia\Domain\Service\Report\HtmlBasedPdfInterface;
use Britannia\Domain\Service\Report\ReportInterface;
use Britannia\Domain\Service\Report\ReportList;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

final class FileDownload
{
    /**
     * @var PdfGenerator
     */
    private PdfGenerator $generator;

    /**
     * @var PdfFormFiller
     */
    private PdfFormFiller $formFiller;


    /**
     * @var string
     */
    private string $pathToTempDir;
    private string  $pathToTemplatesDir;

    public function __construct(PdfGenerator $generator, PdfFormFiller $formFiller, ParameterBagInterface $parameterBag)
    {
        $this->generator = $generator;
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
        foreach ($reportList as $report) {
            $files[] = $this->generateTempPdfFile($report);
        }

        if (empty($files)) {
            return new Response('No hay nada que generar', 404);
        }

        $pathToFile = $files[0];

        if (count($files) > 1) {
            $pathToFile = $this->packFilesIntoZip($reportList->name(), $files);
        }

        return $this->createResponseFromFilePath($pathToFile);
    }

    private function generateTempPdfFile(ReportInterface $report): string
    {
        if ($report instanceof HtmlBasedPdfInterface) {
            return $this->generator->create($report, $this->pathToTempDir());
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
