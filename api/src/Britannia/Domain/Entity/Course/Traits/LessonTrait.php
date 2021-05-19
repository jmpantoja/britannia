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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonList;
use Doctrine\Common\Collections\Collection;

trait LessonTrait
{
    /**
     * @var Collection
     */
    protected $lessons;


    /**
     * @return Lesson[]
     */
    public function lessons(): array
    {
        return $this->lessonList()->toArray();
    }

    /**
     * @return LessonList
     */
    protected function lessonList(): LessonList
    {
        return LessonList::collect($this->lessons);
    }
}
