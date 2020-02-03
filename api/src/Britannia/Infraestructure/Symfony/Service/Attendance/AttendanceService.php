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

namespace Britannia\Infraestructure\Symfony\Service\Attendance;


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\AttendanceRepositoryInterface;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use Carbon\CarbonImmutable;

class AttendanceService
{
    /**
     * @var LessonRepositoryInterface
     */
    private $lessons;
    /**
     * @var AttendanceRepositoryInterface
     */
    private AttendanceRepositoryInterface $attendance;

    public function __construct(LessonRepositoryInterface $lessons, AttendanceRepositoryInterface $attendance)
    {
        $this->lessons = $lessons;
        $this->attendance = $attendance;
    }

    public function numOfAbsencesByTerm(Term $term): int
    {
        return $this->attendance->countByTerm($term);
    }

    public function attendancePercentByTerm(Term $term): float
    {
        $total = $this->lessons->countByTerm($term);
        $numOfAbsences = $this->numOfAbsencesByTerm($term);

        if ($total <= 0) {
            return 100;
        }
        $percent = ($total - $numOfAbsences) * 100 / $total;

        return round($percent, 1);
    }

    public function numOfAbsencesByCourse(Course $course, Student $student): int
    {
        return $this->attendance->countByCourse($course, $student);
    }

    public function attendancePercentByCourse(Course $course, Student $student): float
    {
        $total = $this->lessons->countByCourse($course);
        $numOfAbsences = $this->numOfAbsencesByCourse($course, $student);

        if ($total <= 0) {
            return 100;
        }
        $percent = ($total - $numOfAbsences) * 100 / $total;

        return round($percent, 1);
    }

    public function summary(Student $student, Course $course, int $limit = 5): array
    {
        $values = [];
        $today = CarbonImmutable::now();
        $lessons = $this->lessons->getLastLessonsByCourse($course, $today, $limit);

        foreach ($lessons as $lesson) {
            $value = [
                'title' => $this->title($lesson, $student),
                'status' => $lesson->hasBeenMissing($student) ? 'missed' : 'attended'
            ];

            $values[] = $value;
        }

        return [
            'percent' => 'x',
            'items' => $values
        ];
    }

    private function title(Lesson $lesson, Student $student)
    {
        $date = $lesson->day()->format('d/m/Y');

        $reason = $lesson->whyHasItBeenMissing($student);

        if (is_null($reason)) {
            return $date;
        }

        return sprintf('%s (%s)', $date, $reason);
    }

}
