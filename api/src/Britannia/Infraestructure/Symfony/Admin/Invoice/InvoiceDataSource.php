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

namespace Britannia\Infraestructure\Symfony\Admin\Invoice;


use Britannia\Domain\Entity\Invoice\Invoice;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class InvoiceDataSource extends AdminDataSource
{
    public function __invoke(Invoice $invoice)
    {

        $data['alumno'] = $this->parse($invoice->student());
        $data['descripción'] = $this->parse($invoice->subject());
        $data['fecha creación '] = $this->parse($invoice->createdAt());
        $data['fecha vencimiento'] = $this->parse($invoice->expiredAt());
        $data['precio total'] = $this->parse($invoice->priceTotal());
        $data['descuento total'] = $this->parse($invoice->discountTotal());
        $data['importe total'] = $this->parse($invoice->total());
        $data['tipo'] = $this->parse($invoice->mode());
        $data['pagado'] = $this->parse($invoice->isPaid());
        $data['fecha de pago'] = $this->parse($invoice->paymentDate());

        return $data;
    }

}
