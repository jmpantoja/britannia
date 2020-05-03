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


use PlanB\DDD\Domain\Enum\Enum;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\Enum as EnumConstraint;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class EnumType extends AbstractSingleType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('enum_class');
        $resolver->setAllowedTypes('enum_class', ['string']);
        $resolver->setAllowedValues('enum_class', function (string $value) {
            return is_a($value, Enum::class, true);
        });

        $resolver->addNormalizer('choices', function (OptionsResolver $resolver, $value) {
            $class = $resolver['enum_class'];
            $constants = forward_static_call([$class, 'getConstants']);

            return array_flip($constants);
        });

        $resolver->setDefault('enum_class', $this->getEnumClass());
        parent::configureOptions($resolver);
    }


    public function buildConstraint(array $options): ?Constraint
    {
        $enumClass = $this->getEnumClass();
        return new EnumConstraint($enumClass, [
            'required' => $options['required']
        ]);
    }

    public function customMapping($data)
    {
        $enumClass = $this->getOption('enum_class');
        return forward_static_call([$enumClass, 'byName'], $data);
    }

    abstract public function getEnumClass(): string;


}
