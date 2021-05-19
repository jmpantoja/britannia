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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Pass\Pass;
use Britannia\Domain\VO\Course\Locked\Locked;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\PositiveInteger;

class LessonList extends EntityList
{

    protected function typeName(): string
    {
        return Lesson::class;
    }

    private function futureLessons()
    {
        return $this->values()->filter(function (Lesson $lesson) {
            return $lesson->isFuture();
        });
    }

    public function update(LessonList $lessons, Locked $locked, Course $course, ?Pass $pass = null): self
    {
        if ($locked->isLocked()) {
            return $this;
        }

        $this->clear($locked);
        $lessons->reindex($this->count(), $course, $pass);

        $this->forAddedItems($lessons);

        return $this;
    }

    private function reindex(int $offset, Course $course, ?Pass $pass)
    {
        $number = PositiveInteger::make($offset + 1);
        $this->values()->each(function (Lesson $lesson, int $index) use ($course, $pass, $number) {
            $number = $number->addInteger($index);
            $lesson->attachCourse($number, $course, $pass);
        });
    }

    private function clear(Locked $locked): self
    {
        if ($locked->isReset()) {
            return $this->clearAll();
        }
        return $this->clearOnlyFuture();
    }

    /**
     * @return $this
     */
    private function clearAll(): LessonList
    {
        foreach ($this as $item) {
            $this->remove($item);
        }
        return $this;
    }

    private function clearOnlyFuture(): self
    {
        foreach ($this->futureLessons() as $item) {
            $this->remove($item);
        }

        return $this;
    }

    public function firstDay(): CarbonImmutable
    {
        return $this->values()->first()->day();
    }

    public function lastDay(): CarbonImmutable
    {
        return $this->values()->last()->day();
    }


}
