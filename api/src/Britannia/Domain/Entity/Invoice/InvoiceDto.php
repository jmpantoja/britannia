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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Payment\PaymentMode;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\Price;

final class InvoiceDto extends Dto
{
    public ?Student $student;
    public ?string $subject;
    public CarbonImmutable $createdAt;
    public ?CarbonImmutable $expiredAt = null;
    public Price $total;
    public PaymentMode $mode;
    public bool $paid = false;
    public ?CarbonImmutable $paidAt = null;
    public InvoiceDetailList $details;

    protected function defaults(): array
    {
        return [
            'details' => InvoiceDetailList::collect(),
            'mode' => PaymentMode::CASH(),
            'createdAt' => CarbonImmutable::today()
        ];
    }


}

