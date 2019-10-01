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


use PlanB\DDD\Domain\VO\FullName;

class BankAccount
{
    public static function make(FullName $titular, IBAN $iban, string $city, string $province, int $number)
    {

    }
}
