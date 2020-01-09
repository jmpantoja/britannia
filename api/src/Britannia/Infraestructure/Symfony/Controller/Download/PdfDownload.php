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

namespace Britannia\Infraestructure\Symfony\Controller\Download;


use Britannia\Domain\Service\Report\ReportList;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class PdfDownload extends Download
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

    protected function assetsBase(): string
    {
        return 'http://api';
    }

    public function createResponse(ReportList $reportList): Response
    {
        $report = $reportList->first();
        $output = $this->renderReport($report);

        $pathToFile = $this->generator->createTempFile(...[
            $output,
            $report->fileName(),
            $report->options()
        ]);

        return $this->responseFromFile($pathToFile);
    }

    /**
     * @param $html
     * @return Response
     */
    private function responseFromFile(string $pathToFile): Response
    {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', basename($pathToFile)),
        ];

        return $this->createANewResponse($pathToFile, $headers);
    }

}
