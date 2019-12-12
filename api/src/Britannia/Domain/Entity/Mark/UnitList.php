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

namespace Britannia\Domain\Entity\Mark;


use Britannia\Domain\VO\Mark\NumOfUnits;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\PositiveInteger;

class UnitList
{
    /**
     * @var Unit[]
     */
    private $data;

    private $term;

    private function __construct(Term $term)
    {
        $this->term = $term;
        $this->data = $term->getUnits();
    }

    public static function make(Term $term)
    {

        return new self($term);
    }

    public function updateAmount(NumOfUnits $numOfUnits): self
    {
        $total = $this->data->count();

        $max = $numOfUnits->toInt();

        if ($total === $max) {
            return $this;
        }

        if ($total > $max) {
            $this->purge($max);
            return $this;
        }

        $this->populate($max);

        return $this;
    }


    private function purge(int $max)
    {
        $overflowedUnits = $this->getOverflowUnits($max);

        foreach ($overflowedUnits as $unit) {
            $this->remove($unit);
        }
    }

    private function getOverflowUnits(int $max): Collection
    {
        return $this->data->filter(function (Unit $unit) use ($max) {
            $position = $unit->getNumber()->toInt();
            return $position > $max;
        });
    }


    private function remove(Unit $unit): self
    {
        if ($unit->isCompleted()) {
            return $this;
        }

        $this->data->removeElement($unit);
        return $this;
    }

    /**
     * @param $max
     */
    private function populate(int $max): void
    {
        $total = $this->data->count();
        $total++;
        while ($total <= $max) {
            $unit = Unit::make($this->term, PositiveInteger::make($total));

            $this->data->add($unit);
            $total++;
        }
    }

    public function toCollection(): Collection
    {
        return $this->data;
    }


}
