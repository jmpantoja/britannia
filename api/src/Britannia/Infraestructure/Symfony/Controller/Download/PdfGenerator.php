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


use Knp\Snappy\Pdf;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class PdfGenerator
{
    /**
     * @var Pdf
     */
    private Pdf $pdfGenerator;
    /**
     * @var string
     */
    private $pathToTempDir;

    public function __construct(Pdf $pdfGenerator, ParameterBagInterface $parameterBag)
    {
        $this->pdfGenerator = $pdfGenerator;

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
    public function pathToTempDir(): string
    {
        return $this->pathToTempDir;
    }

    public function createTempFile(string $output, string $fileName, array $options): string
    {
        $pathToFile = sprintf('%s/%s.pdf', $this->pathToTempDir, $fileName);
        $this->pdfGenerator->generateFromHtml($output, $pathToFile, $options);
        return $pathToFile;
    }


}
