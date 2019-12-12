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

namespace Britannia\Infraestructure\Symfony\Form\Type\Mark;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LockedType;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitsDefinitionType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('skills', SetOfSkillsType::class, [
                'label' => false
            ])
            ->add('terms', TermListType::class, [
                'mapped' => false,
                'label' => false
            ]);

        if (!$options['course']->isPending()) {
            $builder
                ->add('locked', LockedType::class, [
                    'label' => false,
                ]);
        }

    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'course' => null,
            'data_class' => UnitsDefinition::class
        ]);

        $resolver->addAllowedTypes('course', [Course::class]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        return UnitsDefinition::make(...[
            $data['skills'],
            $data['terms'],

            $data['locked'] ?? null
        ]);
    }
}
