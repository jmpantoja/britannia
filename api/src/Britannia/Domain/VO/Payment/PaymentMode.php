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


use MabeEnum\Enum;

class PaymentMode extends Enum
{
    public const CASH = 'Al contado';
    public const DAY_1 = 'Domiciliado Día 1';
    public const DAY_10 = 'Domiciliado Día 10';


    public function isCash(): bool
    {
        return $this->is(PaymentMode::CASH());
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
