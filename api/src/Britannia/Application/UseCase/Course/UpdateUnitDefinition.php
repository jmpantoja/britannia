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

namespace Britannia\Application\UseCase\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Mark\UnitDefinitionHasChanged;
use Britannia\Domain\VO\Mark\UnitsDefinition;

class UpdateUnitDefinition
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var UnitsDefinition
     */
    private $unitsDefinition;

    private function __construct(Course $courseId, UnitsDefinition $unitsDefinition)
    {
        $this->course = $courseId;
        $this->unitsDefinition = $unitsDefinition;
    }

    public static function fromEvent(UnitDefinitionHasChanged $event): self
    {
        return new self($event->getCourse(), $event->getUnitDefinition());
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
    public function getUnitsDefinition(): UnitsDefinition
    {
        return $this->unitsDefinition;
    }


}
