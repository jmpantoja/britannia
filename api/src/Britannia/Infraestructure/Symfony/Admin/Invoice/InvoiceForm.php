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
use Britannia\Domain\Entity\Invoice\InvoiceId;
use Britannia\Infraestructure\Symfony\Form\Type\Invoice\InvoiceDetailListType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class InvoiceForm extends AdminForm
{
    public function configure(Invoice $invoice)
    {

        $isPaid = $invoice->isPaid();
        $isEditing = $invoice->id() instanceof InvoiceId;

        $this->add('subject', TextType::class, [
            'label' => 'Descripción',
            'disabled' => $isPaid

        ]);

        $this->add('student', null, [
            'disabled' => $isEditing || $isPaid,
            'label' => 'Alumno',
        ]);

        $this->add('details', InvoiceDetailListType::class, [
            'disabled' => $isPaid,
            'label' => false,
//            'asadasd'=>23424
        ]);
    }

}
