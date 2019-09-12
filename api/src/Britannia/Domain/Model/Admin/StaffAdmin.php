<?php

declare(strict_types=1);

namespace Britannia\Domain\Model\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class StaffAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('userName')
            ->add('password')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
            ->add('id')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('userName')
            ->add('password')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
            ->add('id')
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
            ->add('userName')
            ->add('password')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
            ->add('id')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('userName')
            ->add('password')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
            ->add('id')
            ;
    }
}
