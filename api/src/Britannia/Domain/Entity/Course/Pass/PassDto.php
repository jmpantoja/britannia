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

namespace Britannia\Domain\Entity\Course\Pass;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\Pass\PassHours;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\Dto;

final class PassDto extends Dto
{
    public Course $course;
    public PassHours $hours;
    public CarbonImmutable $start;
    public LessonList $lessonList;

    protected function defaults(): array
    {
        return [
            'lessonList' => LessonList::collect()
        ];
    }


}
