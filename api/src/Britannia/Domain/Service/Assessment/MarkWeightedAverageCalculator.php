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


use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use PlanB\DDD\Domain\VO\Percent;

final class MarkWeightedAverageCalculator
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
        $units = $this->units->toFloat($skill);
        $exam = $this->exam->toFloat($skill);

        if (is_null($units) || is_null($exam)) {
            return Mark::notAssessment();
        }

        $unitsWeighted = $units * $this->unitsWeight->toFloat();
        $examWeighted = $exam * $this->examWeight->toFloat();

        $average = $unitsWeighted + $examWeighted;
        return Mark::make($average);
    }
}
