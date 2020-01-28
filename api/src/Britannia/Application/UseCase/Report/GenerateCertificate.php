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

class GenerateCertificate implements ReportCommandInterface
{
    /**
     * @var Course
     */
    private $course;

    /**
     * @var array
     */
    private array $students;

    /**
     * @param Course $course
     * @param Student[] $students
     * @return GenerateCertificate
     */
    public static function make(Course $course, array $students): self
    {
        return new self($course, $students);
    }

    private function __construct(Course $course, array $students)
    {
        $this->course = $course;
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
     * @return array
     */
    public function students(): array
    {
        return $this->students;
    }
}
