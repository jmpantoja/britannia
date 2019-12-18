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

namespace Britannia\Infraestructure\Symfony\Form\Type\Lesson;


use Britannia\Domain\Entity\Course\Attendance;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $student = $options['student'];
        $lesson = $options['lesson'];
        $builder
            ->add('missed', AttendanceToggleType::class, [
                'label' => false,
                'student' => $student,
                'lesson' => $lesson

            ])
            ->add('reason', null, [
                'label' => 'Motivo',
                'data' => $lesson->whyHasItBeenMissing($student)
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options); // TODO: Change the autogenerated stub
        $view->vars['student'] = $options['student'];
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'lesson',
            'student'
        ]);

        $resolver->setAllowedTypes('lesson', Lesson::class);
        $resolver->setAllowedTypes('student', Student::class);

        $resolver->setDefaults([
            'data_class' => Lesson::class
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        if (empty($data['missed'])) {
            return null;
        }

        $reason = $data['reason'] ?? null;

        return Attendance::make($this->getLesson(), $this->getStudent(), $reason);
    }

    /**
     * @return Lesson
     */
    public function getLesson(): Lesson
    {
        return $this->getOption('lesson');
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->getOption('student');
    }
}
