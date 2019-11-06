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
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TimeSheetType extends AbstractCompoundType
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('dayOfWeek', DayOfWeekType::class, [
                'disabled' => $options['locked']
            ])
            ->add('startTime', DateTimePickerType::class, [
                'label' => 'Hora',
                'dp_pick_date' => false,
                'format' => 'h:mm a', //YYYY-MM-D h:mm:ss a
                'dp_minute_stepping' => 5,
                'required' => false,
                'disabled' => $options['locked']
            ])
            ->add('length', PositiveIntegerType::class, [
                'disabled' => $options['locked']
            ])
            ->add('classroomId', ClassRoomType::class, [
                'disabled' => $options['locked']
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeSheet::class,
            'locked' => false
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return TimeSheet::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return TimeSheet::make(...[
            $data['dayOfWeek'],
            $data['startTime'],
            $data['length'],
            $data['classroomId'],
        ]);

    }
}


