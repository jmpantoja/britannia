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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentId;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelativesType extends ModelType
{

    public function getBlockPrefix()
    {
        return self::MULTISELECT;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['studentId']);
        $resolver->setAllowedTypes('studentId', [StudentId::class, 'null']);

        $resolver->setDefaults([
            'class' => Student::class,
            'sonata_help' => 'Seleccione otros alumnos de la misma familia',
        ]);
    }


    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $studentId = $resolver['studentId'];
        return $builder
            ->where('A.id != :id')
            ->setCacheable(true)
            ->setParameter('id', $studentId);
    }

    public function customMapping($data)
    {
        return StudentList::collect($data);
    }
}
