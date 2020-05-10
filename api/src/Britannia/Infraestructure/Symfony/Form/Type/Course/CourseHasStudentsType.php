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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\Entity\Student\StudentList;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseHasStudentsType extends ModelType
{

    public function getBlockPrefix()
    {
        return self::MULTISELECT;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Student::class,
            'property' => 'fullName.reversedMode',
        ]);

        $resolver->setRequired([
            'course'
        ]);

        $resolver->setAllowedTypes('course', [Course::class]);
    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {

        $course = $resolver['course'];

        if ($course->isAdult()) {
            $builder->where('A.age >= 17');
        }

        if ($course->isSchool() or $course->isSupport()) {
            $builder->where('A.age >= 6 AND A.age <= 20');
        }

        if ($course->isPreSchool()) {
            $builder->where('A.age <= 6');
        }

        $builder->setCacheable(false);

        return $builder;
    }

    /**
     * @param StudentCourse[] $value
     * @return Student[]
     */
    public function transform($value)
    {
        return StudentCourseList::collect($value)
            ->onlyActives()
            ->toStudentList()
            ->toArray();
    }

    public function customMapping($students)
    {
        return StudentList::collect($students);
    }

}
