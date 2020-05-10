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
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LockedType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Carbon\CarbonImmutable;
use IntlDateFormatter;
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
        /** @var Course $course */
        $course = $options['course'];

        $start = $course->start();
        $end = $course->end();
        $schedule = $course->schedule();


        $builder
            ->add('start', DatePickerType::class, [
                'format' => \IntlDateFormatter::LONG,
                'label' => 'Inicio',
                'data' => $start,
                'attr' => [
                    'data-disabled' => $options['course']->isActive()
                ]
            ])
            ->add('end', DatePickerType::class, [
                'format' => \IntlDateFormatter::LONG,
                'data' => $end,
                'label' => 'Fin'
            ])
            ->add('schedule', CollectionType::class, [
                'label' => 'Horario',
                'data' => $schedule->toArray(),
                'entry_type' => TimeSheetType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true
            ]);

        if (!$options['course']->isPending()) {
            $builder
                ->add('locked', LockedType::class, [
                    'label' => false,
                    'msg_update' => 'Se <b>descartará</b> la información de las <b>lecciones que aún no se han producido</b><br/>pero se <b>conservará la de las lecciones ya pasadas</b><br/><br/>Elija esta opción si no quiere perder el control de asistencia.',
                    'msg_reset' => 'Se <b>borrará la información de todas las lecciones</b>, incluidas las ya pasadas<br/><br/>Esto implica que <b>se perderá el control de asistencia</b>'
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

    public function buildConstraint(array $options): ?Constraint
    {
        return TimeTable::buildConstraint($options);
    }

    protected function getValueFromData($data, $name)
    {
        $value = parent::getValueFromData($data, $name);

        if ($value instanceof Schedule) {
            $value = $value->toArray();
        }
        return $value;
    }


    public function customMapping(array $data)
    {
        $schedule = Schedule::fromArray($data['schedule']);

        $timeRange = TimeRange::make(...[
            CarbonImmutable::make($data['start']),
            CarbonImmutable::make($data['end'])
        ]);

        return TimeTable::make(...[
            $timeRange,
            $schedule,
            $data['locked'] ?? null
        ]);

    }

}


