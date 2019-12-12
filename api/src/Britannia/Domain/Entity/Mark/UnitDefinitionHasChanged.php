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

namespace Britannia\Domain\Entity\Mark;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use PlanB\DDD\Domain\Event\DomainEvent;

class UnitDefinitionHasChanged extends DomainEvent
{

    /**
     * @var Course
     */
    private $course;
    /**
     * @var TimeTable
     */
    private $unitDefinition;

    private function __construct(Course $course, UnitsDefinition $unitDefinition)
    {
        $this->course = $course;
        $this->unitDefinition = $unitDefinition;
    }

    public static function make(Course $course, UnitsDefinition $unitsDefinition): self
    {
        return new self($course, $unitsDefinition);
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return UnitsDefinition
     */
    public function getUnitDefinition(): UnitsDefinition
    {
        return $this->unitDefinition;
    }


}
