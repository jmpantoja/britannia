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


use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostalAddressType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('address', TextType::class)
            ->add('postalCode', PostalCodeType::class, [
                'required' => $options['required'],
                'error_bubbling' => true,
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'data_class' => PostalAddress::class,
        ]);
    }


    public function customMapping(FormDataMapper $mapper)
    {

        $mapper
            ->try(function (array $data) {
                return PostalAddress::make(...[
                    $data['address'],
                    $data['postalCode']
                ]);
            })
            ->catch(function ($messages) {
                $messages['postalCode'] = 'No es un código postal correcto (ej. 11500)';
                return $messages;
            });

    }
}
