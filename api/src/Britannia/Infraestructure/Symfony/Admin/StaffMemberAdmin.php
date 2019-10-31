<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Form\RoleType;
use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

final class StaffMemberAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('userName')
            ->add('teacher', null, [
                'editable' => true
            ])
            ->add('password')
            ->add('active')
            ->add('roles')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('userName')
            ->add('fullName')
            ->add('teacher', null, [
                'editable' => true
            ])
            ->add('active')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {

        $atCreated = (bool) $this->isCurrentRoute('create');
        $subject = $this->getSubject();

        $formMapper
            ->with('Personal', ['tab' => true])
                ->with('Acceso', ['class' => 'col-md-3'])
                    ->add('userName')
                    ->add('plain_password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'required' => $atCreated,
                        'first_options'  => ['label' => 'Password'],
                        'second_options' => ['label' => 'Repeat Password'],
                    ])
                    ->add('roles', RoleType::class)
                ->end()
                ->with('Contacto', ['class' => 'col-md-5'])
                    ->add('fullName', FullNameType::class)
                    ->add('address', PostalAddressType::class, [
                        'required' => false
                    ])
                    ->add('emails', EmailListType::class)
                    ->add('phoneNumbers', PhoneNumberListType::class)
                ->end()

                ->ifTrue($subject->getTeacher())
                ->with('Cursos', ['class' => 'col-md-4'])
                    ->add('courses')
                ->end()
                ->ifEnd()

            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('teacher')
            ->add('userName')
            ->add('password')
            ->add('email')
            ->add('fullName')
            ->add('active')
            ->add('roles')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id');
    }

}
