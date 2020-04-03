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

namespace Britannia\Infraestructure\Symfony\Admin\Staff;


use Britannia\Domain\Entity\Staff\Photo;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Form\Type\Photo\PhotoType;
use Britannia\Infraestructure\Symfony\Form\Type\Staff\RoleType;
use Britannia\Infraestructure\Symfony\Form\Type\Staff\TeacherHasCoursesType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class StaffForm extends AdminForm
{

    public function configure(StaffMember $staffMember, bool $hasRootPrivileges)
    {
        $this->contactTab('Contacto', $staffMember);
        $this->accessTab('Acceso', $staffMember, $hasRootPrivileges);
        $this->coursesTab('Cursos', $staffMember, $hasRootPrivileges);
    }

    private function contactTab(string $name, StaffMember $staffMember)
    {
        $this->tab($name);

        $this->group('Foto', ['class' => 'col-md-3'])
            ->add('photo', PhotoType::class, [
                'label' => false,
                'owner' => $staffMember,
                'data_class' => Photo::class
            ]);

        $this->group('Personal', ['class' => 'col-md-4'])
            ->add('fullName', FullNameType::class)
            ->add('address', PostalAddressType::class, [
                'required' => false
            ])
            ->add('dni', DNIType::class, [
                'required' => false
            ]);

        $this->group('Contacto', ['class' => 'col-md-4'])
            ->add('emails', EmailListType::class)
            ->add('phoneNumbers', PhoneNumberListType::class);

        return $this;
    }

    private function accessTab(string $name, StaffMember $staffMember, bool $hasRootPrivileges)
    {
        $this->tab($name);

        $constraints = [];
        $creating = null === $staffMember->id();

        if ($creating) {
            $constraints = [new NotBlank()];
        }

        $group = $this->group('Acceso', ['class' => 'col-md-4'])
            ->add('userName', null, [
                'attr' => [
                    'style' => 'width:450px'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Los passwords deben coincidir.',
                'required' => $creating,
                'first_options' => ['label' => 'Password',
                    'constraints' => $constraints,
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'style' => 'width:300px'
                    ]],
                'second_options' => [
                    'constraints' => $constraints,
                    'label' => 'Repeat Password',
                    'attr' => [
                        'style' => 'width:300px'
                    ]

                ],
            ]);

        if ($hasRootPrivileges) {
            $this->group('Roles', ['class' => 'col-md-4'])
                ->add('roles', RoleType::class, [
                    'label' => false,
                    'required' => false
                ]);
        }

    }

    private function coursesTab(string $name, StaffMember $staffMember, $hasRootPrivileges)
    {

        if (!$hasRootPrivileges || !$staffMember->isTeacher()) {
            return;
        }

        $this->tab($name);
        $this->tab('Cursos');
        $this->group('Cursos en Activo', ['class' => 'col-md-12']);

        $this->add('courses', TeacherHasCoursesType::class, [
            'label' => 'Cursos',
        ], [
            'admin_code' => 'admin.course'
        ]);
    }


}
