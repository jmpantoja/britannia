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

namespace Britannia\Infraestructure\Symfony\Form\Report\CourseMarks;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\CourseInfoData;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChooseStudentListType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['data'];

        foreach ($course->students() as $student) {
            $key = (string)$student->id();

            $builder->add($key, ChooseStudentType::class, [
                'mapped' => false,
                'student' => $student,
                'data' => false
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => Course::class,
            'mapped' => false,
            'attr' => [
                'novalidate' => 'true'
            ]
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
        /** @var Course $course */
        $course = $this->getOption('data');
        $students = $course->students();

        $keys = collect($data)
            ->filter(fn($value) => $value === false)
            ->keys()
            ->toArray();

        $filtered = collect($students)
            ->filter(function (Student $student) use ($keys) {
                $key = (string)$student->id();
                return in_array($key, $keys);
            })
            ->toArray();

        return $filtered;
    }
}
