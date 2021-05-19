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


use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeachersType extends ModelType
{

    public function getBlockPrefix()
    {
        return self::MULTISELECT;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => StaffMember::class,
            'property' => 'fullName.reversedMode'
        ]);

    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $builder->where('A.teacher = :yes')
            ->setParameter('yes', true)
            ->setCacheable(true);
    }

    public function customMapping($students)
    {
        return StaffList::collect($students);
    }
}
