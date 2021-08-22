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

namespace Britannia\Domain\VO\Course\Payment;


use Carbon\CarbonImmutable;
use DateTimeInterface;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

final class DatedPayment
{
    use Validable;

    private Price $price;
    private CarbonImmutable $deadline;

    private function __construct(Price $price, CarbonImmutable $deadline)
    {
        $this->price = $price;
        $this->deadline = $deadline;
    }

    static public function make(Price $price, DateTimeInterface $deadline)
    {
        return new self($price, CarbonImmutable::make($deadline));
    }

    /**
     * @return Price
     */
    public function price(): Price
    {
        return $this->price;
    }

    /**
     * @return CarbonImmutable
     */
    public function deadline(): CarbonImmutable
    {
        return $this->deadline;
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\DatedPayment(['required' => $options['required']]);
    }
}
