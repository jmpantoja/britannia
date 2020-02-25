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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\OneToOne;


use Britannia\Domain\Entity\Course\Course\OneToOne;
use Britannia\Domain\Entity\Course\Pass\PassList;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassListType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var OneToOne $course */
        $course = $options['course'];

        $builder->add('passes', CollectionType::class, [
            'required' => true,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'error_bubbling' => false,
            'data' => $course->passes(),
            'entry_type' => PassType::class,
            'entry_options' => [
                'course' => $course
            ]
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('course');
        $resolver->setAllowedTypes('course', [OneToOne::class]);
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
        $passes = $data['passes'];

        return PassList::collect($passes);
    }
}
