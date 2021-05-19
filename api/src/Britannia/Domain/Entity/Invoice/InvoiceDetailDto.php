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

namespace Britannia\Domain\Entity\Invoice;


use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;

final class InvoiceDetailDto extends Dto
{
    public string $subject;
    public PositiveInteger $numOfUnits;
    public Percent $discount;
    public Price $price;
}

