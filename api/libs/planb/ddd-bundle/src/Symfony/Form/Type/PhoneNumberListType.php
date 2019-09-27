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

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDDBundle\ApiPlattform\DataPersister;
use Sonata\AdminBundle\Form\Type\CollectionType as SymfonyCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;

//use Symfony\Component\Form\Extension\Core\Type\CollectionType as SymfonyCollectionType;

/**
 * This type wrap native `collection` form type and render `add` and `delete`
 * buttons in standard Symfony` collection form type.
 *
 * @final since sonata-project/admin-bundle 3.52
 *
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
class PhoneNumberListType extends AbstractType implements DataTransformerInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);

    }

    public function getParent()
    {
        return SymfonyCollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'required' => false,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'entry_type' => PhoneNumberType::class
        ]);
    }

    /**
     * NEXT_MAJOR: Remove when dropping Symfony <2.8 support.
     *
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return '';
    }


    /**
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value)
    {
        return (array)$value;
    }

    /**
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($value)
    {

        if (is_null($value)) {
            return $value;
        }

        $value = array_filter($value);

        try {
            $value = $this->convertToPhoneNumberList($value);
        } catch (\Exception $exception) {
            throw new TransformationFailedException($exception->getMessage());
        }

        return $value;
    }

    /**
     * @param $value
     * @return array
     */
    protected function convertToPhoneNumberList($value): array
    {
        $value = array_map(function ($item) {
            return PhoneNumber::make((string)$item);
        }, $value);
        return $value;
    }


}
