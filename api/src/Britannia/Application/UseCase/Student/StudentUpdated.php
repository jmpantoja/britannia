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

namespace Britannia\Application\UseCase\Student;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;

final class StudentUpdated
{

    /**
     * @var Student
     */
    private Student $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public static function make(Student $student)
    {
        return new self($student);
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

}
