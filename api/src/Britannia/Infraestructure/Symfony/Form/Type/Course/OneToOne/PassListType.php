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
use Britannia\Domain\VO\Course\Pass\PassInfo;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LockedType;
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

        if (!$options['course']->isPending()) {
            $builder
                ->add('locked', LockedType::class, [
                    'label' => false,
                    'msg_update' => 'Se <b>descartará</b> la información de las <b>lecciones que aún no se han producido</b><br/>pero se <b>conservará la de las lecciones ya pasadas</b><br/><br/>Elija esta opción si no quiere perder el control de asistencia.',
                    'msg_reset' => 'Se <b>borrará la información de todas las lecciones</b>, incluidas las ya pasadas<br/><br/>Esto implica que <b>se perderá el control de asistencia</b>'
                ]);
        }

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
        $locked = $data['locked'];

        $passList = PassList::collect($passes);

        return PassInfo::make($passList, $locked);
    }
}
