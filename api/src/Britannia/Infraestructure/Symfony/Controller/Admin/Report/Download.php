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


use Britannia\Domain\Service\Report\ReportInterface;
use Britannia\Domain\Service\Report\ReportList;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class Download implements DownloadInterface
{
    /**
     * @var Environment
     */
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    abstract public function createResponse(ReportList $reportList): Response;

    /**
     * @param ReportInterface $report
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function renderReport(ReportInterface $report): string
    {
        $template = $this->templateByReport($report);
        $params = $this->paramsByReport($report);

        return $this->twig->render($template, $params);
    }

    /**
     * @param ReportInterface $report
     * @return string
     */
    protected function templateByReport(ReportInterface $report): string
    {
        return ClassnameToTemplate::make($report)
            ->filter();
    }

    /**
     * @param ReportInterface $report
     * @param bool $debug
     * @return array
     */
    private function paramsByReport(ReportInterface $report): array
    {
        return array_merge([
            'title' => $report->title(),
            'assets_base' => $this->assetsBase()
        ], $report->params());
    }

    /**
     * @return string
     */
    protected function assetsBase(): string
    {
        return '';
    }

    /**
     * @param string $pathToFile
     * @param array $headers
     * @return Response
     */
    protected function createANewResponse(string $pathToFile, array $headers): Response
    {
        $content = file_get_contents($pathToFile);
        (new Filesystem())->remove(dirname($pathToFile));

        return new Response($content, 200, $headers);
    }

}
