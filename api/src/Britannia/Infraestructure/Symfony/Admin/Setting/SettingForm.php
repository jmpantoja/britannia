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
use Britannia\Infraestructure\Symfony\Form\Type\Course\EnrollmentPaymentType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;

final class SettingForm extends AdminForm
{
    public function configure()
    {
        $this->tabPrice('Precios');
        $this->tabLopd('Cláusulas');

    }

    private function tabPrice(string $tabName)
    {
        $this->tab($tabName);

        $this->group('Precios', ['class' => 'col-md-3'])
            ->add('enrollmentPayment', EnrollmentPaymentType::class, [
                'label' => 'Matrícula',
                'show_unlock' => false
            ])
            ->add('monthlyPayment', PriceType::class, [
                'label' => 'Mensualidad',
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
