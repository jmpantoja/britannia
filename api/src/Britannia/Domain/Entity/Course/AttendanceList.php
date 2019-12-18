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


use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\Model\EntityList;

final class AttendanceList extends EntityList
{
    protected function __construct(Attendance ...$items)
    {
        parent::__construct($items);
    }

    public function findByStudent(Student $student)
    {
        return $this->data()->
        filter(function (Attendance $attendance) use ($student) {
            return $attendance->isOfStudent($student);

        })->first();
    }
}
