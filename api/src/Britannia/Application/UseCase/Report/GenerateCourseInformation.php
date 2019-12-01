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

namespace Britannia\Application\UseCase\Report;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;

class GenerateCourseInformation
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var Student
     */
    private $student;

    public static function make(Course $course, ?Student $student): self
    {
        return new self($course, $student);
    }

    protected function __construct(Course $course, ?Student $student)
    {

        $this->course = $course;
        $this->student = $student;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return Student
     */
    public function getStudent(): ?Student
    {
        return $this->student;
    }


}
