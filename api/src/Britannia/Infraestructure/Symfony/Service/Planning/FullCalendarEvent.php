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
use Britannia\Domain\Entity\Course\Lesson;
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
        $classRoom = $lesson->getClassRoom();
        $course = $lesson->getCourse();
        $color = $this->getColorByCourse($course);

        $start = $lesson->getStart();
        $end = $lesson->getEnd();

        $startTime = $lesson->getStartTime();
        $endTime = $lesson->getEndTime();

        $this->withResource($classRoom)
            ->withTitle($course)
            ->withLimits($start, $end)
            ->withColor($color)
            ->withSchedule($startTime, $endTime)
            ->withAttendances($course, $lesson);

    }

    private function getColorByCourse(Course $course): RGBA
    {
        return $course->getColor();
    }

    private function withAttendances(Course $course, Lesson $lesson): self
    {
        $students = iterator_to_array($course->getStudents());

        $this->attendances = array_map(function (Student $student) use ($lesson) {
            return $this->createAttendance($student, $lesson);
        }, $students);

        return $this;
    }

    private function createAttendance(Student $student, Lesson $lesson): array
    {
        $status = $lesson->getStatusByStudent($student);
        $student = $this->formatStudentNameWithReason($student, $lesson);

        return [
            'status' => $status,
            'student' => $student,
        ];
    }

    private function formatStudentNameWithReason(Student $student, Lesson $lesson)
    {
        $name = $student->getFullName();
        $reason = $this->getFormattedReason($student, $lesson);

        $student = sprintf('%s %s', $name, $reason);
        return trim($student);
    }

    /**
     * @param Student $student
     * @param Lesson $lesson
     * @return null|string
     */
    private function getFormattedReason(Student $student, Lesson $lesson)
    {
        $reason = $lesson->getMissedReasonByStudent($student);
        $reason = empty($reason) ? '' : sprintf('(%s)', $reason);
        return $reason;
    }

    private function withSchedule(CarbonImmutable $startTime, CarbonImmutable $endTime): self
    {
        $this->schedule = sprintf('de %s a %s', ...[
            $startTime->toTimeString('minute'),
            $endTime->toTimeString('minute')
        ]);
        return $this;
    }

    private function withColor(RGBA $color): self
    {
        $this->color = $color->toHtml();
        return $this;
    }

    private function withLimits(CarbonImmutable $start, CarbonImmutable $end): self
    {
        $this->start = $start->toAtomString();
        $this->end = $end->toAtomString();

        return $this;
    }

    private function withTitle(Course $course): self
    {
        $this->title = (string)$course->getName();
        return $this;
    }

    private function withResource(ClassRoom $classRoom): self
    {
        $this->resourceId = (string)$classRoom->getId();
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
