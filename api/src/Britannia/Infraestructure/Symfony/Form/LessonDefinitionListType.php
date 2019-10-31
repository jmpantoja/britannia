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

namespace Britannia\Infraestructure\Symfony\Form;


use Britannia\Domain\VO\LessonDefinition;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;


class LessonDefinitionListType extends AbstractSingleType
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
            'entry_type' => LessonDefinitionType::class,
//            'constraints' => [
//                new Count([
//                    'min' => 1,
//                    'minMessage' => 'Se necesita {{ limit }} clase como mínimo.|Se necesitan {{ limit }} clase como mínimo.',
//                    'maxMessage' => 'Se necesita {{ limit }} clases como máximo.|Se necesitan {{ limit }} clases como máximo.',
//                    'exactMessage' => 'Se necesita exactamente {{ limit }} clases.|Se necesitan exactamente {{ limit }} clases.',
//                ])
//            ]
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
        return array_map(function ($lessonDefinition) {
            return $this->toLessonDefinition($lessonDefinition);
        }, $data);

    }

    /**
     * @param $lessonDefinition
     * @return LessonDefinition|null
     */
    protected function toLessonDefinition($lessonDefinition): ?LessonDefinition
    {
        if (!($lessonDefinition instanceof LessonDefinition)) {
            return null;
        }

        return $lessonDefinition;
    }

}


