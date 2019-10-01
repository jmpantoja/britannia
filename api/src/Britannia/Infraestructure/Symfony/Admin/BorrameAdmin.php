<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\JobType;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class BorrameAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName')
            ->add('address.address')
            ->add('address.postalCode');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName')
            ->add('address.address')
            ->add('address.postalCode')
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
        $formMapper
            ->add('fullName', FullNameType::class, [
                'label' => 'Nombre del alumno',
                'required' => false
            ])
            ->add('address', PostalAddressType::class, [
                'label' => 'Dirección Postal',
                'required' => false
            ])
            ->add('job', JobType::class, [
                'label' => 'Profesión',
                'required' => true
            ])
            ->add('dni', DNIType::class);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName')
            ->add('address.address')
            ->add('address.postalCode');
    }
}
