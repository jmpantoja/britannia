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

namespace Britannia\Application\UseCase\Report;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Repository\TermsParametersInterface;
use Britannia\Domain\Service\Report\CourseCertificate;
use Britannia\Domain\Service\Report\ReportList;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class GenerateCertificateUseCase implements UseCaseInterface
{

    public function handle(GenerateCertificate $command)
    {
        /** @var Course $course */
        $course = $command->course();
        $students = $command->students();

        $studentsKeys = collect($students)
            ->map(fn(Student $student) => (string)$student->id())
            ->toArray();

        $reports = collect($course->courseHasStudents())
            ->filter(function (StudentCourse $studentCourse) use ($studentsKeys) {
                $studentsKey = (string)$studentCourse->student()->id();
                return in_array($studentsKey, $studentsKeys);
            })
            ->map(fn(StudentCourse $studentCourse) => CourseCertificate::make($studentCourse))
            ->toArray();

        return ReportList::make($course->name(), $reports);
    }

}
