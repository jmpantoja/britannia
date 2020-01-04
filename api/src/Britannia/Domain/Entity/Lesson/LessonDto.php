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

namespace Britannia\Domain\Entity\Lesson;


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\Course\AttendanceList;
use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\Dto;

final class LessonDto extends Dto
{
    public ClassRoom $classRoom;
    public TimeSheet $timeSheet;
    public CarbonImmutable $date;
    public AttendanceList $attendances;

    protected function defaults(): array
    {
        return [
            'attendances' => AttendanceList::collect(),
        ];
    }

}
