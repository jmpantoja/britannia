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
use Cocur\Slugify\Slugify;
use Knp\Snappy\Pdf;
use Twig\Environment;

final class PdfGenerator
{
    /**
     * @var Pdf
     */
    private Pdf $htmlToPdfParser;

    /**
     * @var Environment
     */
    private Environment $twig;

    public function __construct(Pdf $htmlToPdfParser, Environment $twig)
    {
        $this->htmlToPdfParser = $htmlToPdfParser;
        $this->twig = $twig;

    }

    public function create(HtmlBasedPdfInterface $report, string $pathToTempDir): string
    {
        $output = $this->renderReport($report);

        $fileName = Slugify::create()->slugify($report->title());
        $pathToFile = sprintf('%s/%s.pdf', $pathToTempDir, $fileName);

        $this->htmlToPdfParser->generateFromHtml($output, $pathToFile, $report->options());

        return $pathToFile;

    }

    /**
     * @param ReportInterface $report
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderReport(HtmlBasedPdfInterface $report, array $params = []): string
    {
        $template = $this->templateByReport($report);
        $params = $this->paramsFromReport($report, $params);


        return $this->twig->render($template, $params);
    }

    /**
     * @param ReportInterface $report
     * @return string
     */
    private function templateByReport(HtmlBasedPdfInterface $report): string
    {
        return ClassnameToTemplate::make($report)
            ->filter();
    }

    /**
     * @param ReportInterface $report
     * @param bool $debug
     * @return array
     */
    private function paramsFromReport(HtmlBasedPdfInterface $report, array $params): array
    {
        return array_merge([
            'title' => $report->title(),
            'assets_base' => 'http://api'
        ], $report->params(), $params);
    }


}
