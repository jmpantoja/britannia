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


use Britannia\Domain\Entity\Book\Book;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseHasBooksType extends ModelType
{

    public function getBlockPrefix()
    {
        return self::MULTISELECT;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Book::class,
            'label' => 'Material',
            'property' => 'name',
            'btn_add' => false
        ]);
    }

    public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A')
    {
        $builder->setCacheable(true);
    }
}
