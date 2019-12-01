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

namespace Britannia\Domain\VO;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use Traversable;

class FamilyOrderedList implements \IteratorAggregate
{
    private $list = [];

    public static function make(Student $student): self
    {
        return new self($student);
    }

    private function __construct(Student $student)
    {
        $this->list = $this->buildSortedList($student);

    }

    /**
     * @param Student $student
     *
     * @return Student[]
     */
    private function buildSortedList(Student $student): array
    {
        $tempList = [];

        $relatives = $student->getRelatives();
        foreach ($relatives as $relative) {
            $tempList[] = $relative;
        }
        $tempList[] = $student;
        usort($tempList, [$this, 'compare']);

        return array_reverse($tempList);

    }

    private function compare(Student $studentA, Student $studentB)
    {
        $first = $this->calculePayment($studentA);
        $second = $this->calculePayment($studentB);

        $value = $first->compare($second);
        if ($value === 0) {
            $value = strcmp((string)$studentA->getId(), (string)$studentB->getId());
        }

        return $value;

    }

    private function calculePayment(Student $student): Price
    {
        $initial = 0.0;
        $active = CourseStatus::ACTIVE();
        $courses = $student->findCoursesByStatus($active);

        $payment = array_reduce($courses->toArray(), function (float $total, Course $course) {
            $price = $course->getMonthlyPayment();
            return $total + $price->toFloat();
        }, $initial);

        return Price::make($payment);
    }

    public function getOrderOf(Student $student): PositiveInteger
    {
        $order = array_search($student, $this->list, false);
        return PositiveInteger::make($order + 1);
    }

    /**
     * @return \ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }


}
