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


use Britannia\Domain\Entity\Staff\StaffMember;
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
    /**
     * @var StaffMember
     */
    private StaffMember $staffMember;
    /**
     * @var bool
     */
    private bool $hasRootPrivileges;

    private function isRoot(): bool
    {
        return $this->hasRootPrivileges;
    }

    private function isTeacher()
    {
        return $this->staffMember->isTeacher();
    }

    private function isNewStaffMember(): bool
    {
        return null === $this->staffMember->id();
    }

    public function configure(StaffMember $staffMember, bool $hasRootPrivileges)
    {
        $this->hasRootPrivileges = $hasRootPrivileges;
        $this->staffMember = $staffMember;

        $this->firstTab();
    }

    private function firstTab()
    {
        $this->tab('Personal');

        $constraints = [];
        if ($this->isNewStaffMember()) {
            $constraints = [new NotBlank()];
        }

        $group = $this->group('Acceso', ['class' => 'col-md-3'])
            ->add('userName', null, [
            //    'label'=>'userName'
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => $this->isNewStaffMember(),
                'first_options' => ['label' => 'Password',
                    'constraints' => $constraints,
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ]],
                'second_options' => [
                    'constraints' => $constraints,
                    'label' => 'Repeat Password'
                ],
            ]);

        if ($this->isRoot()) {
            $group->add('roles', RoleType::class, [
                'required' => false
            ]);
        }

        $this->group('Personal', ['class' => 'col-md-5'])
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


        if ($this->isRoot() && $this->isTeacher()) {
            $this->tab('Cursos');
            $this->group('Cursos en Activo', ['class' => 'col-md-12']);

            $this->add('courses', TeacherHasCoursesType::class, [
                'label' => 'Cursos',
            ], [
                'admin_code' => 'admin.course'
            ]);
        }

        return $this;
    }
}
