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

namespace Britannia\Domain\Service\Course;


use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class LessonList
{
    private $days = [];
    private $data = [];


    public static function make(?Collection $collection = null): self
    {
        $collection = $collection ?? new ArrayCollection();

        return new self($collection);
    }

    private function __construct(Collection $collection)
    {
        $this->data = $collection;

        foreach ($this->data as $item) {

            if($item->isFuture()){
                $this->data->removeElement($item);
                continue;
            }

            $key = $this->getKey($item);
            $this->days[$key] = true;
        }
    }

    private function getKey(Lesson $lesson): string
    {
        return $lesson->getDay()->format('Y-m-d');
    }

    private function addLesson(Lesson $lesson): self
    {
        $key = $this->getKey($lesson);

        if (isset($this->days[$key])) {
            return $this;
        }

        $this->days[$key] = true;
        $this->data->add($lesson);


        return $this;
    }

    public function add(Course $course, ClassRoom $classRoom, Calendar $day, TimeSheet $timeSheet): self
    {
        $number = count($this->data) + 1;
        $date = $day->getDate();

        $lesson = Lesson::make($number, $course, $classRoom, $date, $timeSheet);
        $this->addLesson($lesson);

        return $this;
    }

    public function toCollection()
    {

        return $this->data;
    }
}
