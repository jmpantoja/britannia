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

namespace Britannia\Application\UseCase\CourseReport;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Discount\StudentDiscount;

class GenerateCourseInformation implements CourseReportCommandInterface
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var StudentDiscount
     */
    private $discount;
    private bool $singlePaid;

    /**
     * GenerateCourseInformation constructor.
     * @param Course $course
     * @param StudentDiscount $discount
     */
    protected function __construct(Course $course, StudentDiscount $discount, bool $singlePaid)
    {
        $this->course = $course;
        $this->discount = $discount;
        $this->singlePaid = $singlePaid;
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return static
     */
    public static function make(Course $course, StudentDiscount $discount, bool $singlePaid = true): self
    {
        return new self($course, $discount, $singlePaid);
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return StudentDiscount
     */
    public function discount(): StudentDiscount
    {
        return $this->discount;
    }

    /**
     * @return bool
     */
    public function isSinglePaid(): bool
    {
        return $this->singlePaid;
    }

}
