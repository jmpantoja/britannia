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


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class InvoiceDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->add('mode', 'string', [
            'header_style' => 'width:75px',
            'row_align' => 'left',
            'label' => false,
            'template' => 'admin/invoice/invoice_mode_column.html.twig',
        ]);

        $this->addIdentifier('subject', 'string', [
            'header_style' => 'width:250px',
            'label' => 'Descripción',
            'template' => 'admin/invoice/invoice_subject_column.html.twig',
        ]);

        $this->add('student.fullName.lastName', 'string', [
            'row_align' => 'left',
            'label' => 'Alumno',
            'template' => 'admin/invoice/invoice_student_column.html.twig',
        ]);

        $this->add('paymentDate', 'date', [
            'header_style' => 'width:120px',
            'row_align' => 'left',
            'label' => 'Pagado el',
            'template' => 'admin/invoice/invoice_paid_at_column.html.twig',
        ]);

        $this->add('createdAt', 'date', [
            'header_style' => 'width:120px',
            'row_align' => 'left',
            'label' => 'Creado el',
            'template' => 'admin/invoice/invoice_created_at_column.html.twig',
        ]);

        $this->add('total', null, [
            'header_style' => 'width:80px',
            'row_align' => 'center',
            'label' => 'Total',
            'template' => 'admin/invoice/invoice_total_column.html.twig',
        ]);

        $this->add('_action', null, [
            'label' => 'Pagar',
            'header_style' => 'width:130px;',
            'row_align' => 'left',
            'actions' => [
                'report-info' => [
                    'template' => 'admin/invoice/invoice_report_action.html.twig'
                ]
            ]
        ]);
    }
}
