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
use Britannia\Domain\Service\Report\ReportInterface;
use Laminas\Filter\Word\CamelCaseToSeparator;

final class ClassnameToTemplate
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private string $extension;

    public static function make(ReportInterface $report): self
    {
        return new self($report);
    }

    private function __construct(ReportInterface $report)
    {
        $fqn = get_class($report);
        $pieces = explode('\\', $fqn);
        $this->className = array_pop($pieces);

        $this->extension = 'html.twig';
        if ($report instanceof FormBasedPdfInteface) {
            $this->extension = 'pdf';
        }
    }

    /**
     * @inheritDoc
     */
    public function main(): string
    {
        $filter = new CamelCaseToSeparator('_');
        $className = $filter->filter($this->className);

        return sprintf('admin/report/%s.%s', strtolower($className), $this->extension);
    }

    /**
     * @inheritDoc
     */
    public function footer(): string
    {
        $filter = new CamelCaseToSeparator('_');
        $className = $filter->filter($this->className);

        return sprintf('admin/report/%s_footer.%s', strtolower($className), $this->extension);
    }
}
