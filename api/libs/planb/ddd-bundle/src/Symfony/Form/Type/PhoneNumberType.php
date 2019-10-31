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

namespace PlanB\DDDBundle\Symfony\Form\Type;


use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PhoneNumberType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phoneNumber', InputType::class, [
                'pre_icon' => 'fa fa-phone'
            ])
            ->add('description', InputType::class, [
                'required' => false
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhoneNumber::class,
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return PhoneNumber::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return PhoneNumber::make(...[
            $data['phoneNumber'],
            $data['description']
        ]);
    }
}
