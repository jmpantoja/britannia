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
use Britannia\Domain\VO\Discount\StudentDiscount;

class GenerateCourseInformation
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var StudentDiscount
     */
    private $discount;

    public static function make(Course $course, StudentDiscount $discount): self
    {
        return new self($course, $discount);
    }

    protected function __construct(Course $course, ?StudentDiscount $discount)
    {
        $this->course = $course;
        $this->discount = $discount;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return StudentDiscount
     */
    public function getDiscount(): ?StudentDiscount
    {
        return $this->discount;
    }


}
