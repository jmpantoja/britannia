<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\RoleType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add('email')
            ->add('firstName')
            ->add('lastName')
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
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('teacher', null, [
                'editable' => true
            ])
            ->add('active', null, [
                'editable' => true
            ])
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

        $formMapper
            ->with('Personal', ['tab' => true])
                ->with('Acceso', ['class' => 'col-md-6'])
                    ->add('active')
                    ->add('userName')
                    ->add('plain_password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'required' => $atCreated,
                        'first_options'  => ['label' => 'Password'],
                        'second_options' => ['label' => 'Repeat Password'],
                    ])
                ->end()
                ->with('Contacto', ['class' => 'col-md-6'])
                    ->add('firstName')
                    ->add('lastName')
                    ->add('email', EmailType::class)
                ->end()
            ->end()
            ->with('Permisos', ['tab' => true])
                ->add('roles', RoleType::class, [
                    'multiple' => true,
                    'expanded' => true
                ])
                ->end()
            ->end();
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('teacher')
            ->add('userName')
            ->add('password')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
            ->add('roles')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id');
    }

    public function toString($object)
    {
        $pieces = explode('\\', $this->getClass());
        $name = array_pop($pieces);

        return sprintf('%s [%s]', $name, $object->getId());
    }
}
