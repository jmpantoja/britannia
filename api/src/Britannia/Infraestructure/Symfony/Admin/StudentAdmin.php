<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Student\Adult;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints\NotBlank;

final class StudentAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('active')
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('active')
            ->addIdentifier('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName')
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
        $isAdult = $this->getSubject() instanceof Adult;

        $formMapper
            ->with('Personal', ['tab'=>true])
                ->with('Alumno', ['class'=>'col-md-4'])
                    ->add('fullName', FullNameType::class, [
                        'label' => false,
                        'required' => true
                    ])
                    ->add('birthDate', DatePickerType::class, [
                        'empty_data' => "\r",
                        'required' => true
                    ])
                    ->ifTrue($isAdult)
                        ->add('dni', DNIType::class, [
                            'empty_data' => null,
                            'required' => false
                        ])
                    ->ifEnd()
                ->end()
                ->with('Contacto', ['class'=>'col-md-4'])
                    ->add('email', EmailType::class, [
                        'required' => false
                    ])
                    ->add('address', PostalAddressType::class, [
                        'label' => false,
                        'required' => false
                    ])
                ->end()
            ->end();


    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('active')
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName');
    }
}
