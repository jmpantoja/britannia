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


abstract class TemplateBasedXlsxReport implements TemplateBasedInteface
{


    private string $title;

    protected function __construct(string $title, array $params = [])
    {
        $this->title = $title;
        $this->params = $params;
    }

    final public function extension(): string
    {
        return 'xlsx';
    }

    public function setValue(int $sheet, string $cell, array $data): self
    {
        $this->params['sheets'][$sheet][$cell] = (array)$data;
        return $this;
    }

    public function params(): array
    {
        return $this->params;
    }

    public function title(): string
    {
        return $this->title;
    }
}
