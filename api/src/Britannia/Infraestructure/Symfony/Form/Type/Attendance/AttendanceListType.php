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

namespace Britannia\Infraestructure\Symfony\Form\Type\Attendance;


use Britannia\Domain\Entity\Attendance\AttendanceList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceListType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['course'];
        $lesson = $options['lesson'];
        $date = $options['date'];

        $builder->add('date', DatePickerType::class, [
            'data' => $date,
            'label' => false,
        ]);

        $students = $course->students();
        if ($lesson instanceof Lesson) {
            $students = $lesson->students();
        }

        foreach ($students as $student) {
            $key = (string)$student->id();
            $builder->add($key, AttendanceType::class, [
                'label' => false,
                'required' => false,
                'student' => $student,
                'lesson' => $lesson
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'course',
            'lesson',
            'date'
        ]);

        $resolver->setAllowedTypes('course', Course::class);
        $resolver->setAllowedTypes('lesson', [Lesson::class, 'null']);
        $resolver->setAllowedTypes('date', CarbonImmutable::class);
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        unset($data['date']);

        $data = array_filter($data);
        return AttendanceList::collect($data);
    }
}
