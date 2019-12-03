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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Discount\Discount;
use Britannia\Domain\VO\Discount\FamilyOrderedList;
use Britannia\Domain\VO\Student\Job\Job;
use Doctrine\Common\Collections\Collection;

class DiscountBuilder
{

    private $familyOrder;
    private $hasFreeEnrollment;
    private $jobStatus;

    public static function make(): self
    {
        return new self();
    }

    private function __construct()
    {
    }


    public function withRelatives(Student $student, Collection $relatives): self
    {
        $familyList = FamilyOrderedList::make($student);
        $this->familyOrder = $familyList->getOrderOf($student);

        return $this;
    }

    public function withEnrollement(Collection $courses, bool $isFreeEnrollement): self
    {
        if ($isFreeEnrollement === true) {
            $this->hasFreeEnrollment = true;
            return $this;
        }

        $this->hasFreeEnrollment = $courses->count() > 1;

        return $this;
    }

    public function withJob(?Job $job): self
    {
        if (is_null($job)) {
            return $this;
        }

        $this->jobStatus = $job->getStatus();
        return $this;
    }

    public function build(): Discount
    {

        return Discount::make($this->familyOrder, $this->jobStatus, $this->hasFreeEnrollment);

    }
}
