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


use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LessonFilterType extends AbstractCompoundType
{

    /**
     * @var LessonRepositoryInterface
     */
    private $repository;

    public function __construct(LessonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTimeImmutable();
        $lesson = $this->repository->findOneBy([
            'day' => $today
        ]);

        $day = $lesson ? $lesson->getDay() : $today;
        $courseId = $lesson ? $lesson->getCourse()->getId() : '';

        $builder
            ->add('day', DatePickerType::class, [
                'label' => 'Fecha',
                'empty_data' => $day->format('M d, Y')
            ])
            ->add('course', CourseFilterType::class, [
                'label' => 'Curso',
                'teacher' => $options['teacher'],
                'placeholder' => null,
                'empty_data' => (string)$courseId
            ]);

    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'teacher'
        ]);

        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        $values = array_filter($data);
        if (empty($values)) {
            return null;
        }

        return $this->repository->findOneBy([
            'day' => \DateTimeImmutable::createFromMutable($data['day']),
            'course' => $data['course']
        ]);

    }
}

