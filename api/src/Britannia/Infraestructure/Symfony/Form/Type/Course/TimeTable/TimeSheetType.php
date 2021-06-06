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


use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Britannia\Infraestructure\Symfony\Form\Type\Date\TimeType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
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
                'required' => true
            ])
            ->add('start', TimeType::class, [
                'label' => 'Inicio',
            ])
            ->add('end', TimeType::class, [
                'label' => 'Fin',
            ])
            ->add('classroomId', ClassRoomType::class, [
                'required' => true
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeSheet::class,
            'required' => true
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return TimeSheet::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return TimeSheet::make(...[
            $data['dayOfWeek'],
            CarbonImmutable::instance($data['start']),
            CarbonImmutable::instance($data['end']),
            $data['classroomId'],
        ]);
    }
}


