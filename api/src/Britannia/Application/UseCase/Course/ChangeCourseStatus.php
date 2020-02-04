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
use Britannia\Domain\Entity\Course\CourseHasChangedStatus;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Course\CourseStatus;

final class ChangeCourseStatus
{
    /**
     * @var Course
     */
    private Course $course;
    /**
     * @var CourseStatus
     */
    private CourseStatus $status;

    public static function fromEvent(CourseHasChangedStatus $event): self
    {
        return new self($event->course(), $event->status());
    }

    private function __construct(Course $course, CourseStatus $status)
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

    /**
     * @return Student[]
     */
    public function students(): array
    {
        return $this->course->students();
    }

}
