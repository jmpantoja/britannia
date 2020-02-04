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

namespace Britannia\Infraestructure\Symfony\Admin\Tutor;


use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;

final class TutorForm extends AdminForm
{

    public function configure()
    {
        $this->tab('Personal');
        $this->group('Datos personales', ['class' => 'col-md-4'])
            ->add('fullName', FullNameType::class, [
                'sonata_help' => 'Escriba el nombre y apellidos del alumno',
                'label' => 'Nombre y apellidos',
                'required' => true
            ])
            ->add('dni', DNIType::class, [
                'empty_data' => null,
                'required' => false
            ]);

        $this->group('Contacto', ['class' => 'col-md-4'])
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
            ]);

        $this->group('Profesión', ['class' => 'col-md-4'])
            ->add('job', JobType::class, [
                'sonata_help' => 'Escriba una ocupación y una situación laboral',
                'required' => false,
                'label' => 'Ocupación'
            ]);
    }
}
