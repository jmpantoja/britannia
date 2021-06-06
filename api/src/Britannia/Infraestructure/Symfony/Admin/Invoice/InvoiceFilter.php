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


use Britannia\Domain\VO\Payment\PaymentMode;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateRangeType;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateType;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\Form\Type\DateRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class InvoiceFilter extends AdminFilter
{

    public function configure()
    {
        $this->add('subject', null, [
            'label' => 'Título'
        ]);

        $this->add('student', null, [
                'label' => 'Alumno',
                'show_filter' => true,
                'advanced_filter' => false,
                'field_options' => [
                    'placeholder' => 'Ver todos'
                ]
            ]
        );

        $this->add('paid', null, [
            'label' => 'Pagado'
        ]);

        $this->add('mode', 'doctrine_orm_choice', [
                'show_filter' => true,
                'advanced_filter' => false,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => array_flip(PaymentMode::getConstants()),
                    'placeholder' => 'Ver todos'
                ]
            ]
        );

        $this->add('createdAt', 'doctrine_orm_date_range', [
            'label' => false,
            'field_type' => DateRangePickerType::class,
            'field_options' => [
                'field_type' => DateType::class,
                'field_options' => [
                ],
                'field_options_start' => [
                    'label' => 'Desde',
                    'required' => false,
                    'invalid_message' => null,
                ],
                'field_options_end' => [
                    'label' => 'Hasta',
                    'required' => false,
                    'invalid_message' => null,
                ]
            ],
            'advanced_filter' => false,
            'show_filter' => true
        ]);


    }
}
