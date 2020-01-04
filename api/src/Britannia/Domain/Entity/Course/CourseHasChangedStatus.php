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


use Britannia\Domain\VO\Course\CourseStatus;
use PlanB\DDD\Domain\Event\DomainEvent;

final class CourseHasChangedStatus extends DomainEvent
{

    /**
     * @var Course
     */
    private Course $course;
    /**
     * @var CourseStatus
     */
    private CourseStatus $status;

    public static function make(Course $course, CourseStatus $status): self
    {
        return new self($course, $status);
    }

    public function __construct(Course $course, CourseStatus $status)
    {
        $this->course = $course;
        $this->status = $status;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return CourseStatus
     */
    public function status(): CourseStatus
    {
        return $this->status;
    }
}
