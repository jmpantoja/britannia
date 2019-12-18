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

namespace Britannia\Domain\Service\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Mark\Unit;
use Britannia\Domain\Entity\Mark\UnitList;
use Britannia\Domain\VO\Mark\NumOfUnits;
use Britannia\Domain\VO\Mark\Term;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use PlanB\DDD\Domain\VO\PositiveInteger;

final class UnitGenerator
{

    /**
     * @param UnitsDefinition $unitsDefinition
     * @return UnitList
     */
    public function generateList(Course $course, UnitsDefinition $unitsDefinition): UnitList
    {
        $terms = $unitsDefinition->terms();

        $temp = [];
        foreach ($terms as $term) {
            $temp[] = $this->makeUnits($course, $term->term(), $term->numOfUnits());
        }

        return UnitList::collect(array_merge(...$temp));
    }


    private function makeUnits(Course $course, Term $term, NumOfUnits $numOfUnits): array
    {
        $units = [];

        $total = $numOfUnits->toInt();
        $count = $this->existingUnitsByTerm($course, $term);

        while ($count < $total) {
            $number = PositiveInteger::make($count + 1);
            $units[] = Unit::make($course, $term, $number);
            $count++;
        }

        return $units;
    }

    private function existingUnitsByTerm(Course $course, Term $term): int
    {
        return UnitList::collect($course->units())
            ->numOfCompletdUnits($term);
    }

}
