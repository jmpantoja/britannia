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
use Britannia\Domain\VO\Course\Locked\Locked;
use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\PositiveInteger;

class LessonList extends EntityList
{
    protected function __construct(Lesson ...$items)
    {
        parent::__construct($items);
    }

    private function futureLessons()
    {
        return $this->data()->filter(function (Lesson $lesson) {
            return $lesson->isFuture();
        });
    }

    public function update(LessonList $lessons, Locked $locked, Course $course): self
    {
        if ($locked->isLocked()) {
            return $this;
        }

        $this->clear($locked);
        $lessons->reindex($course, $this->count());

        $this->forAddedItems($lessons);

        return $this;
    }

    private function reindex(Course $course, int $offset)
    {
        $number = PositiveInteger::make($offset + 1);
        $this->data()->each(function (Lesson $lesson, int $index) use ($course, $number) {
            $number = $number->addInteger($index);
            $lesson->attach($course, $number);
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

}
