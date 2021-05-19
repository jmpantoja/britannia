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

namespace Britannia\Application\UseCase\CourseReport;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Assessment\TermName;

class GenerateTermMarks implements CourseReportCommandInterface
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var TermName
     */
    private TermName $termName;
    /**
     * @var array
     */
    private array $students;

    /**
     * @param Course $course
     * @param TermName $termName
     * @param Student[] $students
     * @return GenerateTermMarks
     */
    public static function make(Course $course, TermName $termName, array $students): self
    {
        return new self($course, $termName, $students);
    }

    private function __construct(Course $course, TermName $termName, array $students)
    {
        $this->course = $course;
        $this->termName = $termName;
        $this->students = $students;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return TermName
     */
    public function termName(): TermName
    {
        return $this->termName;
    }

    /**
     * @return array
     */
    public function students(): array
    {
        return $this->students;
    }
}
