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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Domain\Entity\Attendance\Attendance;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Carbon\CarbonImmutable;

class AttendanceControlBuilder extends BuilderAbstract
{

    private const TYPE = 'Asistencia';

    /**
     * @var Student
     */
    private $student;

    private $lesson;

    private $reason;


    public function initResume(array $input): Resume
    {
        $title = sprintf('%s', ...[
            $input['fecha']
        ]);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withStudent(int $id): self
    {
        $this->student = $this->findOneOrNull(Student::class, [
            'oldId' => $id
        ]);

        return $this;
    }

    public function withReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function withLesson(int $courseId, string $fecha): self
    {
        /** @var Course $course */
        $course = $this->findOneOrNull(Course::class, [
            'oldId' => $courseId
        ]);

        $date = CarbonImmutable::createFromFormat('Y-m-d', $fecha)
            ->setTime(0, 0);



        /**
         *
         * Esta linea solo es necesaria si ya se han camibado las fechas para sumarles un año
         *
         */
       $date = $date->subDay()->addYear();

        $lessons = $course->lessons();
        $this->lesson = null;

        foreach ($lessons as $lesson) {
            $day = $lesson->day();
            if ($date->equalTo($day)) {
                $this->lesson = $lesson;
                break;
            }
        }

        return $this;
    }


    public function build(): ?object
    {
        if (empty($this->lesson)) {

            return null;
        }

        $attendance = Attendance::make($this->lesson, $this->student, $this->reason);

        return $attendance;
    }
}
