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

namespace Britannia\Domain\VO\Assessment;


final class MarkReportList
{
    /**
     * @var MarkReport[]
     */
    private array $markReports;

    public static function collect(iterable $input): self
    {
        return new self(...$input);
    }

    private function __construct(MarkReport ...$reports)
    {

        $this->markReports = $reports;
    }

    public function average(SetOfSkills $skills): MarkReport
    {
        $data = [];
        foreach ($skills as $skill) {
            $data[$skill] = $this->averageBySkill($skill);
        }

        return MarkReport::make($data);
    }

    private function averageBySkill(string $skill): ?Mark
    {
        $average = collect($this->markReports)
            ->map(fn(MarkReport $markReport) => $markReport->toFloat($skill))
            ->average();

        return $average ? Mark::make($average) : null;
    }


}
