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
use Britannia\Domain\Entity\Course\SinglePaymentInterface;
use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Discount\FamilyOrder;
use PlanB\DDD\Domain\VO\Price;

final class FamilyOrderCalculator
{
    private PassPriceList $passPriceList;


    /**
     * FamilyOrderCalculator constructor.
     */
    public function __construct(Setting $setting)
    {
        $this->passPriceList = $setting->passPriceList();
    }

    public function calcule(Student $student): FamilyOrder
    {
        $studentId = (string)$student->id();

        $table = $this->sortedTable($student);

        $upperId = array_key_last($table);
        if ($studentId === $upperId) {
            return FamilyOrder::UPPER();
        }

        $lowerId = array_key_first($table);
        if ($studentId === $lowerId) {
            return FamilyOrder::LOWER();
        }

        return FamilyOrder::DEFAULT();
    }

    /**
     * @param Student $student
     * @return array
     */
    private function sortedTable(Student $student): array
    {
        $temp = [];

        $temp[] = $this->getAmountByStudent($student);

        foreach ($student->relatives() as $relative) {
            $temp[] = $this->getAmountByStudent($relative);
        }

        $table = array_merge(...$temp);
        uasort($table, fn($first, $second) => $this->sort($first, $second));
        return $table;
    }

    private function getAmountByStudent(Student $student): array
    {
        $total = Price::make(0);

        /** @var Course $course */
        foreach ($student->activeCourses() as $course) {
            $price = $this->getPriceByCourse($course);
            $total = $total->add($price);
        }

        return [
            (string)$student->id() => [
                'price' => $total->toFloat(),
                'date' => (int)$student->createdAt()->format('Ymd'),
                'studentId' => (string)$student->id(),
            ]
        ];
    }

    private function getPriceByCourse(Course $course): Price
    {
        if ($course instanceof Course\OneToOne) {
            return $course->priceOfTheMonth($this->passPriceList);
        }

        if ($course instanceof SinglePaymentInterface) {
            return $course->singlePayment()->price();
        }

        return $course->monthlyPayment();
    }


    private function sort(array $first, array $second): int
    {
        return $this->compare($first['price'], $second['price'])
            ?? $this->compare($first['date'], $second['date'])
            ?? $this->compare($first['studentId'], $second['studentId'])
            ?? 0;
    }

    private function compare($first, $second): ?int
    {
        $compare = $first <=> $second;

        if (0 === $compare) {
            return null;
        }

        return $compare;
    }


}
