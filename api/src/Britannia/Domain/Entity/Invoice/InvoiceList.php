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

final class InvoiceList extends EntityList
{

    protected function typeName(): string
    {
        return Invoice::class;
    }
}
