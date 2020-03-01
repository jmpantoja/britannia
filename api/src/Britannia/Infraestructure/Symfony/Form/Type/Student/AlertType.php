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


use Britannia\Domain\VO\Student\Alert\Alert;
use Britannia\Domain\VO\Validator;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alert', CheckboxType::class, [
                'label' => 'El alumno tiene alergias, enfermedades u otra circunstancia especial',
            ])
            ->add('description', WYSIWYGType::class, [
                'label' => false
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {

    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return Alert::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        if (false === (bool)$data['alert']) {
            return Alert::default();
        }

        return Alert::make($data['description']);
    }
}
