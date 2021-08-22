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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course;


use Britannia\Domain\Repository\JobStatusDiscountParametersInterface;
use Britannia\Domain\VO\Course\Payment\DatedPayment;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatedPaymentType extends AbstractCompoundType
{


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', PriceType::class, [
                'label' => 'precio',
                'required' => true
            ])
            ->add('deadLine', DateType::class, [
                'label' => 'fecha límite',
                'required' => true,
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DatedPayment::class
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return DatedPayment::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return DatedPayment::make($data['price'], $data['deadLine']);
    }
}
