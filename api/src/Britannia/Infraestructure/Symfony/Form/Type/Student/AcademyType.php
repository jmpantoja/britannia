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


use Britannia\Domain\Entity\Academy\Academy;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AcademyType extends ModelType
{

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Academy::class,
            'required' => false,
            'label' => 'Academia',
            'btn_add' => 'Crear Academia',
            'expanded' => false,
            'multiple' => false,
            'placeholder' => 'En ninguna otra',
        ]);
    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $builder->setCacheable(true);
    }
}
