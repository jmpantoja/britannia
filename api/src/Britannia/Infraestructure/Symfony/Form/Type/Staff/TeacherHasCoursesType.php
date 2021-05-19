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

namespace Britannia\Infraestructure\Symfony\Form\Type\Staff;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\VO\Course\CourseStatus;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherHasCoursesType extends ModelType
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
            'sonata_help' => 'Seleccione otros alumnos de la misma familia',
        ]);
    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $builder
            ->where('A.timeRange.status= :param')
            ->setParameter('param', CourseStatus::ACTIVE())
            ->setCacheable(false);
    }

    public function customMapping($data)
    {
        return CourseList::collect($data);
    }
}
