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


use Britannia\Domain\VO\BankAccount\BankAccount;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\CityAddressType;
use PlanB\DDDBundle\Symfony\Form\Type\IbanType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BankAccountType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('titular', TextType::class, [
                'label' => 'Titular',
                'required' => $options['required']
            ])
            ->add('iban', IbanType::class, [
                'required' => $options['required']
            ])
            ->add('number', TextType::class, [
                'label' => 'Nº domiciliado',
                'required' => $options['required'],
                'invalid_message' => 'Se necesita un número'
            ])
            ->add('cityAddress', CityAddressType::class, [
                'label' => 'Localidad',
                'required' => $options['required']
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankAccount::class,
            'error_bubbling' => false,
            'required' => false,
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return BankAccount::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return BankAccount::make(...[
            $data['titular'],
            $data['cityAddress'],
            $data['iban'],
            (int)$data['number']
        ]);
    }
}


