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

namespace Britannia\Infraestructure\Symfony\Admin\Attendance;


use Britannia\Domain\Entity\Attendance\Attendance;
use Britannia\Domain\Entity\Lesson\Lesson;
use DomainException;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class AttendanceMapper extends AdminMapper
{

    protected function className(): string
    {
        return Lesson::class;
    }

    protected function create(array $values): Lesson
    {
        throw new DomainException('Este formulario no debe usarse para crear Lecciones');
    }

    /**
     * @param Lesson $lesson
     * @param array $values
     * @return Attendance
     */
    protected function update($lesson, array $values): Lesson
    {
        return  $lesson->updateAttendances($values['attendances']);
    }
}
