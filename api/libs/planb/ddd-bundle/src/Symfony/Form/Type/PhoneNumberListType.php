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
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            'required_message' => 'Se necesita al menos un número de teléfono'

        ]);
    }

    public function transform($value)
    {
        return $value;
    }


    public function customMapping(FormDataMapper $mapper)
    {
        $mapper
            ->try(function ($value) {
                return $this->convertToPhoneNumberList($value);
            });
    }

    /**
     * @param $phoneNumbers
     * @return array
     */
    protected function convertToPhoneNumberList($phoneNumbers): array
    {
        return array_map(function ($phoneNumber) {
            return $this->convertToPhoneNumber($phoneNumber);
        }, $phoneNumbers);
    }

    /**
     * @param $phoneNumber
     * @return PhoneNumber
     */
    protected function convertToPhoneNumber($phoneNumber): PhoneNumber
    {
        if ($phoneNumber instanceof PhoneNumber) {
            return $phoneNumber;
        }

        return PhoneNumber::make((string)$phoneNumber);
    }


}
