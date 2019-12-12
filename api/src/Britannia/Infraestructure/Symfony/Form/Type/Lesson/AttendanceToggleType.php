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


use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceToggleType extends AbstractSingleType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $student = $options['student'];
        $lesson = $options['lesson'];

        $data = $lesson->hasStudentMissed($student);
        $builder->setData($data);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'lesson',
            'student'
        ]);

        $resolver->setAllowedTypes('lesson', Lesson::class);
        $resolver->setAllowedTypes('student', Student::class);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return !empty($data);
    }


}
