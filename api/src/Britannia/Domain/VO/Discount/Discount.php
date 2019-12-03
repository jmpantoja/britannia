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

namespace Britannia\Domain\VO\Discount;


use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Discount\DiscountBuilder;
use Britannia\Domain\VO\Student\Job\JobStatus;
use PlanB\DDD\Domain\VO\PositiveInteger;

class Discount
{

    /**
     * @var JobStatus
     */
    private $jobStatus;
    /**
     * @var int
     */
    private $familyOrder;

    /**
     * @var bool
     */
    private $hasFreeEnrollment;

    public static function byDefault(): self
    {
        $familyOrder = PositiveInteger::make(1);

        return static::make($familyOrder);
    }

    public static function fromStudent(Student $student): self
    {
        $builder = DiscountBuilder::make()
            ->withRelatives($student, $student->getRelatives())
            ->withEnrollement($student->findCoursesByStatus(), $student->isFreeEnrollment());

        if ($student instanceof Adult) {
            $builder->withJob($student->getJob());
        }

        return $builder->build();
    }

    public static function make(PositiveInteger $familyOrder, ?JobStatus $jobStatus = null, bool $hasFreeEnrollment = false): self
    {

        return new self($familyOrder, $jobStatus, $hasFreeEnrollment);
    }


    private function __construct(PositiveInteger $familyOrder, ?JobStatus $jobStatus, bool $hasFreeEnrollment)
    {
        $this->familyOrder = $familyOrder;
        $this->jobStatus = $jobStatus;
        $this->hasFreeEnrollment = $hasFreeEnrollment;
    }

    /**
     * @return JobStatus
     */
    public function getJobStatus(): ?JobStatus
    {
        return $this->jobStatus;
    }

    /**
     * @return int
     */
    public function getFamilyOrder(): PositiveInteger
    {
        return $this->familyOrder;
    }

    public function hasFreeEnrollment(): bool
    {
        return $this->hasFreeEnrollment;
    }

}
