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

namespace Britannia\Application\UseCase\Invoice;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

final class CreateInvoice
{

    /**
     * @var Student
     */
    private Student $student;
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $date;
    /**
     * @var bool
     */
    private bool $overwrite;
    /**
     * @var Course
     */
    private ?Course $course;

    public static function create(Student $student, CarbonImmutable $date)
    {
        return new self($student, $date);
    }

    public static function update(Student $student, Course $course, CarbonImmutable $date)
    {
        return new self($student, $date, $course);
    }

    private function __construct(Student $student, CarbonImmutable $date, Course $course = null)
    {
        $this->student = $student;
        $this->date = $date;
        $this->course = $course;
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

    /**
     * @return Course
     */
    public function course(): ?Course
    {
        return $this->course;
    }

    /**
     * @return CarbonImmutable
     */
    public function date(): CarbonImmutable
    {
        return $this->date;
    }


}
