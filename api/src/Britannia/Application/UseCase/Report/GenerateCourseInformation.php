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
use Britannia\Domain\VO\Discount\StudentDiscount;

class GenerateCourseInformation implements ReportCommandInterface
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var StudentDiscount
     */
    private $discount;

    /**
     * GenerateCourseInformation constructor.
     * @param Course $course
     * @param StudentDiscount|null $discount
     */
    protected function __construct(Course $course, ?StudentDiscount $discount)
    {
        $this->course = $course;
        $this->discount = $discount;
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return static
     */
    public static function make(Course $course, StudentDiscount $discount): self
    {
        return new self($course, $discount);
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
    public function discount(): ?StudentDiscount
    {
        return $this->discount;
    }


}
