<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\BookCategoryType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class BookAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_sort_by' => 'name',
    ];

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('category')
            ->add('price');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('name', null, [
                'label' => 'Libro',
                'template' => 'admin/core/resume_column.html.twig',
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name')
            ->add('category', BookCategoryType::class)
            ->add('price', PriceType::class)
            ->add('courses');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name')
            ->add('category')
            ->add('price');
    }
}
