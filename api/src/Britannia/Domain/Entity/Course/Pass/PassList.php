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


use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use PlanB\DDD\Domain\Model\EntityList;

final class PassList extends EntityList
{
    protected function typeName(): string
    {
        return Pass::class;
    }

    public function lessonList(): LessonList
    {
        $carry = $this->values()
            ->map(fn(Pass $pass) => $pass->lessons());

        $lessons = array_merge(...$carry);
        return LessonList::collect($lessons);
    }

    public function timeRange(): TimeRange
    {
        $start = $this->values()
            ->map(fn(Pass $pass) => $pass->start())
            ->min();

        $end = $this->values()
            ->map(fn(Pass $pass) => $pass->end())
            ->max();

        return TimeRange::make($start, $end);
    }


}
