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

namespace Britannia\Domain\Service\Assessment;


use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;

final class MarkReportAverageCalculator
{


    /**
     * @var MarkReport[]
     */
    private array $markReports;

    public static function collect(MarkReport ...$markReports): self
    {
        return new self(...$markReports);
    }

    public function __construct(MarkReport ...$markReports)
    {
        $this->markReports = $markReports;
        $this->total = count($markReports);
    }

    public function calcule(): MarkReport
    {

        $reports = collect($this->markReports)
            ->map(fn(MarkReport $markReport) => $markReport->toArray())
            ->reduce($this->carry(), []);

        $input = collect($reports)
            ->map(fn(float $total) => $total / $this->total)
            ->toArray();

        return MarkReport::make($input);
    }

    private function carry()
    {
        return function (array $carry, array $report) {
            foreach (SetOfSkills::SET_OF_SIX() as $skill) {
                $data[$skill] = ($carry[$skill] ?? 0) + ($report[$skill] ?? 0);
            }
            return $data;
        };
    }
}
