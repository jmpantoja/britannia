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


use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\RefundPrice;

final class InvoiceDetailList extends EntityList
{

    protected function typeName(): string
    {
        return InvoiceDetail::class;
    }

    public function total(): RefundPrice
    {
        $total = $this->values()
            ->sum(fn(InvoiceDetail $detail) => $detail->total()->toFloat());

        return RefundPrice::make($total);
    }

    public function discountTotal(): RefundPrice
    {
        $total = $this->values()
            ->sum(fn(InvoiceDetail $detail) => $detail->discountTotal()->toFloat());

        return RefundPrice::make($total);
    }

    public function priceTotal(): RefundPrice
    {
        $total = $this->values()
            ->sum(fn(InvoiceDetail $detail) => $detail->priceTotal()->toFloat());

        return RefundPrice::make($total);
    }
}
