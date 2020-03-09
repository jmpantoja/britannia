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


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;

final class FullCalendarAttendance
{
    const PENDING = 'pending';
    const MISSED = 'missed';
    const ATTENDED = 'attended';
    /**
     * @var string
     */
    private string $status;
    /**
     * @var bool
     */
    private bool $hasBeenMissing;
    /**
     * @var string
     */
    private $student;

    public static function make(Student $student, Lesson $lesson): self
    {
        return new self($student, $lesson);
    }

    /**
     * FullCalendarAttendance constructor.
     */
    private function __construct(Student $student, Lesson $lesson)
    {
        $this->initMissedStatus($student, $lesson);

        $this->initStatus($student, $lesson);
        $this->initStudent($student, $lesson);
    }

    /**
     * @param Student $student
     * @param Lesson $lesson
     */
    private function initMissedStatus(Student $student, Lesson $lesson): void
    {
        $this->hasBeenMissing = $lesson->attendanceStatusByStudent($student)->isMissed();
    }

    private function initStatus(Student $student, Lesson $lesson): self
    {
        if ($lesson->isFuture()) {
            $this->status = self::PENDING;
            return $this;
        }

        if ($this->isMissed()) {
            $this->status = self::MISSED;
            return $this;
        }

        $this->status = self::ATTENDED;
        return $this;
    }

    /**
     * @return bool
     */
    private function isMissed(): bool
    {
        return $this->hasBeenMissing;
    }

    private function initStudent(Student $student, Lesson $lesson): self
    {
        $fullName = (string)$student->fullName();

        if (!$this->isMissed()) {
            $this->student = $fullName;
        }

        $this->student = sprintf('%s (%s)', ...[
            $fullName,
            $lesson->whyHasItBeenMissing($student)
        ]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'student' => $this->student
        ];
    }


}
