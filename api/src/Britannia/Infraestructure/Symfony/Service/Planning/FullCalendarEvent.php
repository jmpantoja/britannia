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

namespace Britannia\Infraestructure\Symfony\Service\Planning;


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\RGBA;

class FullCalendarEvent
{
    private $resourceId;

    private $title;

    private $start;

    private $end;

    private $color;

    private $schedule;

    private $attendances = [];

    private function __construct(Lesson $lesson)
    {
        $classRoom = $lesson->classRoom();
        $course = $lesson->course();
        $color = $this->getColorByCourse($course);

        $day = $lesson->day();
        $start = $lesson->startTime();
        $end = $lesson->endTime();

        $this->withResource($classRoom)
            ->withTitle($course)
            ->withLimits($day, $start, $end)
            ->withColor($color)
            ->withAttendances($course, $lesson);

    }

    private function getColorByCourse(Course $course): RGBA
    {
        return RGBA::make(100, 0, 100);
        return $course->getColor();
    }

    private function withAttendances(Course $course, Lesson $lesson): self
    {
        $students = $course->students();

        $this->attendances = array_map(function (Student $student) use ($lesson) {
            return FullCalendarAttendance::make($student, $lesson)
                ->toArray();
        }, $students);

        return $this;
    }

    private function withColor(RGBA $color): self
    {
        $this->color = $color->toHtml();
        return $this;
    }

    private function withLimits(CarbonImmutable $day, CarbonImmutable $start, CarbonImmutable $end): self
    {
        $this->start = $this->applyTimeToDay($day, $start);
        $this->end = $this->applyTimeToDay($day, $end);

        $this->schedule = sprintf('de %s a %s', ...[
            $start->toTimeString('minute'),
            $end->toTimeString('minute')
        ]);

        return $this;
    }

    private function applyTimeToDay(CarbonImmutable $day, CarbonImmutable $time): CarbonImmutable
    {
        $hour = $time->get('hour');
        $minute = $time->get('minute');

        return $day->setTime($hour, $minute);
    }

    private function withTitle(Course $course): self
    {
        $this->title = (string)$course->name();
        return $this;
    }

    private function withResource(ClassRoom $classRoom): self
    {
        $this->resourceId = (string)$classRoom->id();
        return $this;
    }

    public static function fromLesson(Lesson $lesson): self
    {
        return new self($lesson);
    }

    public function toArray(): array
    {
        return [
            'resourceId' => $this->resourceId,
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'color' => $this->color,
            'extendedProps' => [
                'schedule' => $this->schedule,
                'attendances' => $this->attendances
            ]
        ];
    }

}
