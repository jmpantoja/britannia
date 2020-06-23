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
use Britannia\Domain\Service\Report\ReportInterface;
use Britannia\Domain\Service\Report\TemplateBasedInteface;
use Britannia\Domain\Service\Report\TemplateBasedXlsxReport;
use Cocur\Slugify\Slugify;
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
    private string $title;

    public static function make(ReportInterface $report): self
    {
        return new self($report);
    }

    private function __construct(ReportInterface $report)
    {

        $fqn = get_class($report);
        $pieces = explode('\\', $fqn);
        $this->className = array_pop($pieces);
        $this->title = $report->title();

        $this->extension = 'html.twig';

        if($report instanceof TemplateBasedInteface){
            $this->extension = $report->extension();
        }

    }

    public function target()
    {
        $title = Slugify::create()->slugify($this->title);
        return sprintf('%s.%s', $title, $this->extension);
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
