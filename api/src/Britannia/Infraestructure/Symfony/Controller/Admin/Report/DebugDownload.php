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
use Symfony\Component\HttpFoundation\Response;

final class DebugDownload implements DownloadInterface
{
    /**
     * @var PdfGenerator
     */
    private PdfGenerator $generator;

    public function __construct(PdfGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function createResponse(ReportList $reportList): Response
    {
        $html = '';
        foreach ($reportList as $report) {
            $html .= $this->render($report);
        }
        return new Response($html, 200);
    }

    /**
     * @param ReportInterface $report
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function render(ReportInterface $report): string
    {
        if ($report instanceof HtmlBasedPdfInterface) {
            return $this->generator->renderReport($report, [
                'assets_base' => ''
            ]);
        }

        return 'Este informe no tiene vista previa';
    }

}
