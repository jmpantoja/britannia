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

namespace Britannia\Infraestructure\Symfony\Form\Type\Student;


use Britannia\Domain\VO\Payment;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractCompoundType
{


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mode', PaymentModeType::class, [
                'label' => 'Modo de pago'
            ])
            ->add('account', BankAccountType::class, [
                'label' => 'Domiciliación',
                'required' => false
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return Payment::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return Payment::make(...[
            $data['mode'],
            $data['account'],
        ]);
    }
}
