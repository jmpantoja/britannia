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

namespace Britannia\Domain\Service\Payment;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Discount\FamilyOrder;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Carbon\CarbonImmutable;

final class StudentDiscountGenerator
{
    /**
     * @var FamilyOrderCalculator
     */
    private FamilyOrderCalculator $familyOrderCalculator;

    public function __construct(FamilyOrderCalculator $familyOrderCalculator)
    {
        $this->familyOrderCalculator = $familyOrderCalculator;
    }

    public function generate(Student $student, Course $course): StudentDiscount
    {
        return StudentDiscount::make(...[
            $this->getFamilyOrder($student),
            $this->getJobStatus($student),
            $this->getStartDate($student, $course),
            $this->isFreeEnrollment($student)
        ]);
    }

    /**
     * @return mixed
     */
    private function getFamilyOrder(Student $student): FamilyOrder
    {
        return $this->familyOrderCalculator->calcule($student);
    }

    /**
     * @param Student $student
     * @return |null
     */
    private function getJobStatus(Student $student)
    {
        $jobStatus = null;
        if ($student instanceof Adult) {
            $jobStatus = $student->job()->getStatus();
        }
        return $jobStatus;
    }

    /**
     * @param Student $student
     * @param Course $course
     * @return CarbonImmutable
     */
    private function getStartDate(Student $student, Course $course): CarbonImmutable
    {
        return $student->firstDayInCourse($course);
    }

    /**
     * @param Student $student
     * @return bool
     */
    private function isFreeEnrollment(Student $student): bool
    {
        return $student->isFreeEnrollment() || collect($student->studentHasCourses())->isNotEmpty();
    }

}
