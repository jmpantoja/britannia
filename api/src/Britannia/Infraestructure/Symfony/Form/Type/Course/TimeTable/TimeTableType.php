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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\TimeTable;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LockedType;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TimeTableType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DatePickerType::class, [
                'format' => \IntlDateFormatter::LONG,
                'label' => 'Inicio'
            ])
            ->add('end', DatePickerType::class, [
                'format' => \IntlDateFormatter::LONG,
                'label' => 'Fin'
            ])
            ->add('schedule', CollectionType::class, [
                'label' => 'Horario',
                'entry_type' => TimeSheetType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true
            ]);

        if (!$options['course']->isPending()) {
            $builder
                ->add('locked', LockedType::class, [
                    'label' => false,
                ]);
        }

    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeTable::class,
            'label' => 'Calendario',
            'required' => true,
            'course' => null
        ]);

        $resolver->addAllowedTypes('course', [Course::class]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return TimeTable::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        $schedule = array_filter($data['schedule']);


        return TimeTable::make(...[
            CarbonImmutable::make($data['start']),
            CarbonImmutable::make($data['end']),
            $schedule,
            $data['locked'] ?? null
        ]);

    }
}


