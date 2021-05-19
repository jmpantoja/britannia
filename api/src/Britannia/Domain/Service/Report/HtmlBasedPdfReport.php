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

namespace Britannia\Domain\Service\Report;


abstract class HtmlBasedPdfReport implements ReportInterface
{
    /**
     * @var string
     */
    private string $title;

    /**
     * @var array
     */
    private array $params;

    /**
     * @var array
     */
    private array $options;

    protected function __construct(string $title, array $params = [], array $options = [])
    {
        $this->title = $title;
        $this->params = $params;

        $this->options = array_replace([
            'footer-html'=>' '
        ], $options);
    }

    public function addFooter(string $footer): self
    {
        $this->options['footer-html'] = $footer;
        return $this;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

}
