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


use Britannia\Domain\VO\Job;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class JobType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', JobStatusType::class, [
                'label' => 'Situación Laboral',
            ])
            ->add('name', TextType::class, [
                'label' => 'Profesión / Estudios',
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return Job::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        return Job::make(...[
            $data['name'],
            $data['status']
        ]);
    }
}

