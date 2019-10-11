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


use PlanB\DDD\Domain\VO\CityAddress;
use PlanB\DDD\Domain\VO\Exception\InvalidDNIFormatException;
use PlanB\DDD\Domain\VO\Exception\InvalidDNILetterException;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CityAddressType extends AbstractCompoundType
{


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'Ciudad'
            ])
            ->add('province', TextType::class, [
                'label' => 'Provincia'
            ]);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CityAddress::class
        ]);
    }


    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return CityAddress::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return CityAddress::make(...[
            $data['city'],
            $data['province'],
        ]);
    }
}
