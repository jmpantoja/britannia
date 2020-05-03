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

namespace Britannia\Domain\VO\Payment;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self CASH()
 * @method static self DAY_1()
 * @method static self DAY_10()
 */
class PaymentMode extends Enum
{
    private const CASH = 'Al contado';
    private const DAY_1 = 'Domiciliado Día 1';
    private const DAY_10 = 'Domiciliado Día 10';
    
    public function isCash(): bool
    {
        return $this->is(PaymentMode::CASH());
    }

    public function isDayFirst(): bool
    {
        return $this->is(PaymentMode::DAY_1());
    }

    public function isDayTenth(): bool
    {
        return $this->is(PaymentMode::DAY_10());
    }

    public function getDayNumber(): ?int
    {
        if ($this->is(PaymentMode::DAY_1())) {
            return 1;
        }

        if ($this->is(PaymentMode::DAY_10())) {
            return 10;
        }

        return null;
    }
}
