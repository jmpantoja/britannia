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


use Britannia\Domain\VO\TimeSheet;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;


class TimeSheetListType extends AbstractSingleType
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
            'locked' => false,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'error_bubbling' => false,
            'entry_type' => TimeSheetType::class
        ]);

        $resolver->setNormalizer('allow_add', function (OptionsResolver $resolver) {
            return !$resolver['locked'];
        });

        $resolver->setNormalizer('allow_delete', function (OptionsResolver $resolver) {
            return !$resolver['locked'];
        });

        $resolver->setNormalizer('entry_options', function (OptionsResolver $resolver, $values) {
            $values['locked'] = $resolver['locked'];
            return $values;
        });

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
     * @return TimeSheet|null
     */
    protected function toLessonDefinition($lessonDefinition): ?TimeSheet
    {
        if (!($lessonDefinition instanceof TimeSheet)) {
            return null;
        }

        return $lessonDefinition;
    }

}


