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

namespace Britannia\Infraestructure\Symfony\Admin\Setting;


use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\FamilyDiscountListType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\JobStatusDiscountListType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PassPriceListType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\EmailType;
use PlanB\DDDBundle\Symfony\Form\Type\IbanType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Sonata\Form\Type\DateTimeRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SettingForm extends AdminForm
{
    public function configure()
    {
        $this->tabAcademy('Academia');
        $this->tabSepa('Sepa');
        $this->tabPrice('Precios');
        $this->tabLopd('Cláusulas');
    }

    private function tabAcademy(string $tabName)
    {
        $this->tab($tabName);

        $this->group('Contacto', ['class' => 'col-md-4'])
            ->add('phone', PhoneNumberType::class, [
                'label' => 'Fijo'
            ])
            ->add('mobile', PhoneNumberType::class, [
                'label' => 'Movil'
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('web', TextType::class, [
                'label' => 'Web'
            ]);

        $this->group('Horario', ['class' => 'col-md-4'])
            ->add('morning', DateTimeRangePickerType::class, [
                'label' => 'Mañana',
                'block_prefix' => 'time_range',
                'field_options' => [
                    'dp_pick_time' => true,
                    'dp_pick_date' => false,
                    'dp_use_seconds' => false,
                    'dp_use_strict' => true,
                    'format' => 'H:mm'
                ],
                'field_options_start' => [
                    'label' => 'Desde'
                ],
                'field_options_end' => [
                    'label' => 'Hasta'
                ]
            ])
            ->add('afternoon', DateTimeRangePickerType::class, [
                'label' => 'Mañana',
                'block_prefix' => 'time_range',
                'field_options' => [
                    'dp_pick_time' => true,
                    'dp_pick_date' => false,
                    'dp_use_seconds' => false,
                    'dp_use_strict' => true,
                    'format' => 'H:mm'
                ],
                'field_options_start' => [
                    'label' => 'Desde'
                ],
                'field_options_end' => [
                    'label' => 'Hasta'
                ]
            ]);


        $this->group('Redes', ['class' => 'col-md-4'])
            ->add('facebook', TextType::class, [
                'label' => 'Facebook'
            ])
            ->add('twitter', TextType::class, [
                'label' => 'Twitter'
            ]);

    }

    private function tabSepa(string $tabname)
    {
        $this->tab($tabname);
        $this->group('Presentador', ['class' => 'col-md-6'])
            ->add('sepa_presenter_id', TextType::class, [
                'label' => 'Identificador',
                'attr'=>[
                    'style'=>'width:200px'
                ]
            ])
            ->add('sepa_presenter_name', TextType::class, [
                'label' => 'Nombre'
            ])
            ->add('sepa_bbva_office', TextType::class, [
                'label' => 'Oficina Receptora'
            ]);

        $this->group('Acreedor', ['class' => 'col-md-6'])
            ->add('sepa_creditor_id', TextType::class, [
                'label' => 'Identificador'
            ])
            ->add('sepa_creditor_name', TextType::class, [
                'label' => 'Nombre'
            ])
            ->add('sepa_creditor_iban', IbanType::class, [
                'label' => 'Cuenta'
            ]);
    }

    private function tabPrice(string $tabName)
    {
        $this->tab($tabName);

        $this->group('Precios', ['class' => 'col-md-3'])
            ->add('enrollmentPayment', PriceType::class, [
                'label' => 'Matrícula',
                'attr' => [
                    'style' => 'width:120px'
                ]
            ])
            ->add('monthlyPayment', PriceType::class, [
                'label' => 'Mensualidad',
                'attr' => [
                    'style' => 'width:120px'
                ]
            ])
            ->add('passPriceList', PassPriceListType::class, [
                'label' => 'Bonos',
            ]);

        $this->group('Descuento Familiar', ['class' => 'col-md-3'])
            ->add('familyDiscount', FamilyDiscountListType::class, [
                'label' => false
            ]);

        $this->group('Descuento Laboral', ['class' => 'col-md-4'])
            ->add('jobStatusDiscount', JobStatusDiscountListType::class, [
                'label' => false,
                'enable_default_data' => false
            ]);
    }

    private function tabLopd(string $tabName): void
    {

        $this->tab($tabName);
        $this->group('', ['class' => 'col-md-12'])
            ->add('informationClause', WYSIWYGType::class, [
                'label' => 'Cláusula Informativa LOPD'
            ])
            ->add('consentClause', WYSIWYGType::class, [
                'label' => 'Consentimiento LOPD'
            ])
            ->add('generalConsiderationsClause', WYSIWYGType::class, [
                'label' => 'Consideraciones generales LOPD'
            ])
            ->add('cashPaymentsClause', WYSIWYGType::class, [
                'label' => 'Pagos Efectivos LOPD'
            ])
            ->add('personalDataConsentClause', WYSIWYGType::class, [
                'label' => 'Consentimiento de datos personales LOPD'
            ])
            ->add('faqs', WYSIWYGType::class, [
                'label' => 'Preguntas frecuentes'
            ]);
    }

}
