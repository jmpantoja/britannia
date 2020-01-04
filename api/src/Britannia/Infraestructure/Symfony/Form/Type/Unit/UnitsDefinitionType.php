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

namespace Britannia\Infraestructure\Symfony\Form\Type\Unit;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Unit\UnitList;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LockedType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitsDefinitionType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $unitList = UnitList::collect($options['course']->units());

        $builder
            ->add('skills', SetOfSkillsType::class, [
                'label' => false,
                'attr' => [
                    'data-disabled' => true
                ]
            ])
            ->add('terms', TermListType::class, [
                'mapped' => false,
                'label' => false,
                'unitList' => $unitList
            ]);

        if (!$options['course']->isPending()) {
            $builder
                ->add('locked', LockedType::class, [
                    'label' => false,
                    'msg_update' => 'Se <b>conservarán</b> las calificaciones de las unidades ya completadas, <br />pero se pueden eliminar unidades aún sin calificar',
                    'msg_reset' => 'Se <b>borrarán</b> las calificaciones de todas las unidades, <br/>incluidas las que ya han sido completadas'
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
     * @return FullName
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
