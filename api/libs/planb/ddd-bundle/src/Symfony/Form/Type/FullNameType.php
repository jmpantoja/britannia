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


use PlanB\DDD\Domain\VO\Exceptions\FullNameRuleException;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FullNameType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FullName::class,
            'required_message' => 'Se necesita un nombre completo'
        ]);
    }


    public function customMapping(FormDataMapper $mapper)
    {
        $mapper
            ->try(function (array $data) {
                return FullName::make(...[
                    $data['firstName'],
                    $data['lastName'],
                ]);
            });

    }

}

