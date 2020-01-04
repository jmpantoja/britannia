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

namespace Britannia\Domain\Entity\Unit;


use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\Model\EntityList;

final class UnitStudentList extends EntityList
{

    protected function typeName(): string
    {
        return UnitStudent::class;
    }

    public function findByUnit(Unit $unit): iterable
    {
        $data = $this->values()->filter(function (UnitStudent $unitStudent) use ($unit) {
            return $unitStudent->belongToUnit($unit);

        });

        return static::collect($data);
    }

    public function findByStudent(Student $student)
    {
        $data = $this->values()->filter(function (UnitStudent $unitStudent) use ($student) {
            return $unitStudent->belongToStudent($student);
        });

        return static::collect($data);
    }


    public function isCompleted(): bool
    {
        return $this->values()
            ->filter(fn(UnitStudent $unitStudent) => $unitStudent->isCompleted())
            ->isNotEmpty();
    }

}
