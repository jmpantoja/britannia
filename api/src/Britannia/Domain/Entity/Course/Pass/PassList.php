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

namespace Britannia\Domain\Entity\Course\Pass;


use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\Pass\PassHours;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\Price;

final class PassList extends EntityList
{
    protected function typeName(): string
    {
        return Pass::class;
    }

    public function lessonList(): LessonList
    {
        $carry = $this->values()
            ->map(fn(Pass $pass) => $pass->lessons());

        $lessons = array_merge(...$carry);
        return LessonList::collect($lessons);
    }

    public function timeRange(): ?TimeRange
    {
        if ($this->isEmpty()) {
            return null;
        }

        $start = $this->values()
            ->map(fn(Pass $pass) => $pass->start())
            ->min();

        $end = $this->values()
            ->map(fn(Pass $pass) => $pass->end())
            ->max();

        return TimeRange::make($start, $end);
    }

    public function byDate(CarbonImmutable $date): PassList
    {
        $input = $this->values()
            ->filter(fn(Pass $pass) => $pass->timeRange()->contains($date));

        return PassList::collect($input);
    }

    public function totalPrice(PassPriceList $priceList): Price
    {
        $initial = Price::make(0);

        return $this->values()
            ->map(fn(Pass $pass) => $pass->hours())
            ->map(fn(PassHours $passHours) => $priceList->getByPassHours($passHours))
            ->reduce(fn(Price $price, Price $carry) => $carry->add($price), $initial);
    }
}
