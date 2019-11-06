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

namespace Britannia\Domain\Entity\Course;


use PlanB\DDD\Domain\Event\DomainEvent;

class TimeSheetWasChanged extends DomainEvent
{

    /**
     * @var Course
     */
    private $course;

    public static function make(Course $course): self
    {
        return new self($course);
    }

    private function __construct(Course $course)
    {
        if ($course->isStarted()) {
            throw new \LogicException('No se puede modificar el horario de un curso que ya ha comenzado');
        }

        $this->course = $course;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }


}
