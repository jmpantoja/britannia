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

namespace Britannia\Infraestructure\Symfony\Form\Type\Tutor;


use Britannia\Domain\Entity\Student\Tutor;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SelectTutorType extends ModelType
{

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple'=>false,
            'mapped' => false,
            'class' => Tutor::class,
            'label' => false,
            'sonata_help' => 'seleccione un tutor, si ya existe',
            'placeholder' => '-- Nuevo Tutor --'
        ]);
    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $builder->setCacheable(true);
    }
}
