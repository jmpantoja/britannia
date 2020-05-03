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

namespace Britannia\Infraestructure\Symfony\Form\Type\Student;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentHasCoursesType extends ModelType
{
    public function getBlockPrefix()
    {
        return self::MULTISELECT;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Course::class,
            'property' => 'name',
        ]);

    }

    /**
     * @param StudentCourse[] $value
     * @return Course[]
     */
    public function transform($value)
    {
        return StudentCourseList::collect($value)
            ->onlyActives()
            ->toCourseList()
            ->toArray();
    }

    public function customMapping($courses)
    {
        return CourseList::collect($courses);
    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $builder->where('A.timeRange.status != :finalized')
            ->setParameter('finalized', CourseStatus::FINALIZED())
            ->setCacheable(true);

    }
}
