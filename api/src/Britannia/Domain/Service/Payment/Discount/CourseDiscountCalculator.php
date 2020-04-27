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

namespace Britannia\Domain\Service\Payment\Discount;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\Discount\CourseDiscount;
use Britannia\Domain\VO\Student\Job\JobStatus;

final class CourseDiscountCalculator
{
    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return mixed
     */
    public function calculate(Course $course, ?JobStatus $jobStatus): CourseDiscount
    {
        if (is_null($jobStatus)) {
            return CourseDiscount::byDefault();
        }

        $discount = $course->discount();
        return $discount->getByJobStatus($jobStatus) ?? CourseDiscount::byDefault();
    }
}
