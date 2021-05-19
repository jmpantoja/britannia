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


use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;

final class InvoiceHasBeenPaid extends NotificationEvent
{

    private string $invoiceSubject;

    public static function make(Invoice $invoice): self
    {
        return self::builder($invoice->student())
            ->withInvoiceSubject($invoice->subject())
            ->withDate($invoice->paymentDate());
    }

    public function type(): TypeOfNotification
    {
        return TypeOfNotification::INVOICE_PAID();
    }

    protected function makeSubject(): string
    {
        return sprintf('Se ha pagado un recibo a cargo de <b>%s</b> en concepto de <b>%s</b>', ...[
            $this->student->name(),
            $this->invoiceSubject
        ]);
    }

    private function withInvoiceSubject(string $invoiceSubject): self
    {
        $this->invoiceSubject = $invoiceSubject;
        return $this;
    }

}
