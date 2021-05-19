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

namespace Britannia\Application\UseCase\Invoice;


use Britannia\Domain\Entity\Invoice\Invoice;

final class GenerateInvoicePdf
{
    /**
     * @var Invoice
     */
    private Invoice $invoice;
    /**
     * @var bool
     */
    private bool $complete;

    public static function complete(Invoice $invoice): self
    {
        return new self($invoice, true);
    }

    public static function onlyStudent(Invoice $invoice): self
    {
        return new self($invoice, false);
    }

    private function __construct(Invoice $invoice, bool $complete)
    {
        $this->invoice = $invoice;

        $this->complete = $complete;
    }

    /**
     * @return Invoice
     */
    public function invoice(): Invoice
    {
        return $this->invoice;
    }

    public function includeCopyForTheAcademy(): bool
    {
        return $this->complete;
    }
}
