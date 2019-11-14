<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Record\TypeOfRecord;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class RecordAdmin extends AbstractAdmin
{

    public function getExportFields()
    {
        $fields = parent::getExportFields();
        $fields[] = 'student';

        return $fields;
    }


    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);
        return $actions;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('student', null, [
                    'label' => 'Alumno',
                    'show_filter' => true,
                    'advanced_filter' => false,
                    'field_options' => [
                        'placeholder' => 'Ver todos'
                    ]
                ]
            )
            ->add('date', 'doctrine_orm_datetime', [
                'field_type' => DateTimePickerType::class,
                'advanced_filter' => false,
                'show_filter' => true,
                'field_options' => [
                    'dp_pick_time' => false
                ]
            ])
            ->add('type', 'doctrine_orm_choice', [
                    'show_filter' => true,
                    'advanced_filter' => false,
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => array_flip(TypeOfRecord::getConstants()),
                        'placeholder' => 'Ver todos'
                    ]
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper): void
    {

        $listMapper
            ->add('date', null, [
                'header_style' => 'width:120px',
                'row_align' => 'center'
            ])
            ->add('student', 'string', [
                'template' => 'admin/record/record_resume_column.html.twig',
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('date')
            ->add('description')
            ->add('id');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('date')
            ->add('description')
            ->add('id');
    }
}
