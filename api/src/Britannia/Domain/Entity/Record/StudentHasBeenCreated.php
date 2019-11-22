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

namespace Britannia\Domain\Entity\Record;


use Britannia\Domain\Entity\Student\Student;

class StudentHasBeenCreated extends AbstractRecordEvent
{

    public static function make(Student $student): self
    {
        $description = sprintf('Nuevo alumno %s', $student->getFullName());
        $date = $student->getCreatedAt();


        return new self($student, null, $description, $date);
    }

    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::CREATED();
    }
}
