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


use Britannia\Domain\Entity\Student\TutorDto;
use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TutorType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', FullNameType::class, [
                'sonata_help' => 'Escriba el nombre y apellidos del alumno',
                'label' => 'Nombre y apellidos',
                'required' => $options['required']
            ])
            ->add('dni', DNIType::class, [
                'empty_data' => null,
                'required' => false
            ])
            ->add('address', PostalAddressType::class, [
                'sonata_help' => 'Escriba una dirección postal',
                'label' => 'Dirección Postal',
                'required' => false,
            ])
            ->add('emails', EmailListType::class, [
                'required' => true,
                'label' => 'Correos Electrónicos',
            ])
            ->add('phoneNumbers', PhoneNumberListType::class, [
                'required' => false,
                'label' => 'Números de teléfono',
            ])
            ->add('job', JobType::class, [
                'sonata_help' => 'Escriba una ocupación y una situación laboral',
                'required' => false,
                'label' => 'Ocupación'
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('field')
            ->setAllowedTypes('field', ['string']);;

    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['field'] = $options['field'];

        parent::finishView($view, $form, $options);
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
        $values = array_filter(array_values($data));

        if (empty($values)) {
            return null;
        }

        return TutorDto::fromArray($data);
    }
}
