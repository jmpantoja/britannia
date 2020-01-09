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


use PlanB\DDD\Domain\VO\Percent;

class AssessmentDefinition
{
    /**
     * @var SetOfSkills
     */
    private $skills;
    /**
     * @var Percent
     */
    private Percent $unitsWeight;

    private function __construct(SetOfSkills $skills, Percent $unitsWeight)
    {
        $this->skills = $skills;

        $this->unitsWeight = $unitsWeight;
    }

    public static function make(SetOfSkills $skills, Percent $unitsWeight): self
    {
        return new self($skills, $unitsWeight);

    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills;
    }

    /**
     * @return Percent
     */
    public function unitsWeight(): Percent
    {
        return $this->unitsWeight;
    }


    /**
     * @return Percent
     */
    public function examWeight(): Percent
    {
        return $this->unitsWeight;
    }



}
