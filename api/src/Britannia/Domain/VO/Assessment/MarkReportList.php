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
        foreach ($skills->toList() as $skill) {
            $data[$skill] = $this->averageBySkill($skill);
        }

        return MarkReport::make($data);
    }

    private function averageBySkill(string $skill): ?Mark
    {
        if ($this->isMissingSkill($skill)) {
            return null;
        }

        $average = collect($this->markReports)
            ->map(fn(MarkReport $markReport) => $markReport->toFloat($skill))
            ->average();

        return Mark::make($average);
    }

    /**
     * @param string $skill
     * @return bool
     */
    private function isMissingSkill(string $skill): bool
    {
        return collect($this->markReports)
            ->filter(fn(MarkReport $markReport) => $markReport->isMissingSkill($skill))
            ->isNotEmpty();
    }


}
