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
use Britannia\Domain\Entity\Course\CourseId;
use Britannia\Domain\Entity\Course\TimeSheetWasChanged;

class UpdateTimeSheet
{
    /**
     * @var Course
     */
    private $course;

    public static function fromEvent(TimeSheetWasChanged $event): self
    {
        return new self($event->getCourse());
    }

    private function __construct(Course $course)
    {
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
