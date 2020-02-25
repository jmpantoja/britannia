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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\OneToOne;


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonDto;
use Britannia\Domain\Repository\ClassRoomRepositoryInterface;
use Britannia\Domain\VO\Course\Pass\Validator\PassLesson;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TimeTable\ClassRoomType;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PassLessonType extends AbstractCompoundType
{
    /**
     * @var ClassRoomRepositoryInterface
     */
    private ClassRoomRepositoryInterface $classRoomRepository;

    public function __construct(ClassRoomRepositoryInterface $classRoomRepository)
    {

        $this->classRoomRepository = $classRoomRepository;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('day', DatePickerType::class, [
                'label' => 'Fecha'
            ])
            ->add('startTime', DateTimePickerType::class, [
                'label' => 'Inicio',
                'dp_pick_date' => false,
                'dp_pick_time' => true,
                'format' => 'H:mm',
                'dp_minute_stepping' => 5
            ])
            ->add('endTime', DateTimePickerType::class, [
                'label' => 'Fin',
                'dp_pick_date' => false,
                'dp_pick_time' => true,
                'format' => 'H:mm',
                'dp_minute_stepping' => 5
            ])
            ->add('classroomId', ClassRoomType::class, [
                'required' => true
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class
        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new PassLesson();
    }

    public function customMapping(array $data, ?Lesson $lesson = null)
    {

        $dto = LessonDto::fromArray([
            'date' => CarbonImmutable::make($data['day']),
            'start' => CarbonImmutable::make($data['startTime']),
            'end' => CarbonImmutable::make($data['endTime']),
            'classRoom' => $this->classRoomRepository->find($data['classroomId'])
        ]);

        if($lesson instanceof Lesson){
            return $lesson->update($dto);
        }

        return Lesson::make($dto);
    }
}
