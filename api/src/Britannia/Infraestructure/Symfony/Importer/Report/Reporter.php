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

namespace Britannia\Infraestructure\Symfony\Importer\Report;


use Britannia\Infraestructure\Symfony\Importer\Resume;

class Reporter
{

    /**
     * @var ReportInterface[]
     */
    private $reports = [];

    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    public function add(ReportInterface $report): self
    {
        $this->reports[] = $report;
        return $this;
    }

    public function dump(Resume $resume): self
    {
        foreach ($this->reports as $report) {
            $report->dump($resume);
        }

        return $this;
    }

}
