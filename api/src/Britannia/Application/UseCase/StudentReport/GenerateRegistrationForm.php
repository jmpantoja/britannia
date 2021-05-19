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

namespace Britannia\Application\UseCase\StudentReport;


use Britannia\Domain\Entity\Student\Student;

class GenerateRegistrationForm implements StudentReportCommandInterface
{
    /**
     * @var Student
     */
    private Student $student;

    /**
     * @param Student $student
     * @return static
     */
    public static function make(Student $student): self
    {
        return new self($student);
    }

    private function __construct(Student $student)
    {

        $this->student = $student;
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

}
