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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use Carbon\CarbonImmutable;

class AttendanceService
{
    /**
     * @var LessonRepositoryInterface
     */
    private $lessons;

    public function __construct(LessonRepositoryInterface $lessons)
    {
        $this->lessons = $lessons;
    }

    public function getSummary(Student $student, Course $course, int $limit = 5): array
    {
        $values = [];
        $today = CarbonImmutable::now();

        $lessons = $this->lessons->getLastByCourse($course, $today, $limit);


        foreach ($lessons as $lesson) {
            $value = [
                'title' => $this->getTitle($lesson, $student),
                'status' => $lesson->getStatusByStudent($student)
            ];

            $values[] = $value;
        }

        return [
            'percent' => 'x',
            'items' => $values
        ];
    }

    private function getTitle(Lesson $lesson, Student $student)
    {
        $date = $lesson->getDay()->format('d/m/Y');

        $reason = $lesson->getMissedReasonByStudent($student);

        if (is_null($reason)) {
            return $date;
        }


        return sprintf('%s (%s)', $date, $reason);
    }

}
