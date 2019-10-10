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
use PlanB\DDD\Domain\VO\Validator\ArrayLenght;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;

//use Symfony\Component\Form\Extension\Core\Type\CollectionType as SymfonyCollectionType;

/**
 * This type wrap native `collection` form type and render `add` and `delete`
 * buttons in standard Symfony` collection form type.
 *
 * @final since sonata-project/admin-bundle 3.52
 *
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
//class PhoneNumberListType extends AbstractType implements DataTransformerInterface
class PhoneNumberListType extends AbstractSingleType
{

    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function customOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'required' => true,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'error_bubbling' => false,
            'entry_type' => PhoneNumberType::class,
            'constraints' => [
                new Count([
                    'min' => 1,
                    'minMessage' => 'Se necesita {{ limit }} número de teléfono como mínimo.|Se necesitan {{ limit }} números de teléfono como mínimo.',
                    'maxMessage' => 'Se necesita {{ limit }} número de teléfono como máximo.|Se necesitan {{ limit }} números de teléfono como máximo.',
                    'exactMessage' => 'Se necesita exactamente {{ limit }} número de teléfono.|Se necesitan exactamente {{ limit }} números de teléfono.',
                ])
            ]
        ]);
    }

    public function transform($value)
    {
        return $value;
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return (array)$data;
    }
}
