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

namespace Britannia\Domain\VO\Mark;


use PlanB\DDD\Domain\VO\Percent;

final class MarkWeightedAverage
{

    /**
     * @var MarkReport
     */
    private MarkReport $units;
    /**
     * @var MarkReport
     */
    private MarkReport $exam;
    /**
     * @var Percent
     */
    private Percent $unitsWeight;
    /**
     * @var Percent
     */
    private Percent $examWeight;

    public static function make(MarkReport $units, MarkReport $exam, Percent $unitsWeight): self
    {
        return new self($units, $exam, $unitsWeight);
    }

    public function __construct(MarkReport $average, MarkReport $exam, Percent $unitsWeight)
    {
        $this->units = $average;
        $this->exam = $exam;
        $this->unitsWeight = $unitsWeight;
        $this->examWeight = $unitsWeight->complementary();
    }

    public function calcule(SetOfSkills $skills): MarkReport
    {
        $data = [];
        foreach ($skills as $skill) {
            $data[$skill] = $this->weightedAverage($skill);
        }

        return MarkReport::make($data);
    }

    private function weightedAverage(string $skill): Mark
    {
        $units = $this->units->toFloat($skill) * $this->unitsWeight->toFloat();
        $exam = $this->exam->toFloat($skill) * $this->examWeight->toFloat();

        return Mark::make($units + $exam);
    }
}
