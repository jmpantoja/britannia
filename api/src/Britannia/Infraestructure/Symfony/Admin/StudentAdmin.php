<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Student\Adult;
use Britannia\Infraestructure\Symfony\Form\JobType;
use Britannia\Infraestructure\Symfony\Form\PaymentType;
use Britannia\Infraestructure\Symfony\Form\RelativesType;
use PlanB\DDDBundle\Symfony\Form\Type\DateType;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
        $subject = $this->getSubject();
        $isAdult = $subject instanceof Adult;

        $formMapper
            ->with('Personal', ['tab' => true])
                ->with('Alumno', ['class' => 'col-md-4'])
                    ->add('fullName', FullNameType::class, [
                        'label' => false,
                        'required' => true
                    ])
                    ->add('birthDate', DatePickerType::class, [
                        'required' => true

                    ])
                    ->ifTrue($isAdult)
                        ->add('dni', DNIType::class, [
                            'empty_data' => null,
                            'required' => true
                        ])
                    ->ifEnd()
                ->end()
                ->with('Contacto', ['class' => 'col-md-4'])
                    ->add('address', PostalAddressType::class, [
                        'label' => false,
                        'required' => true
                    ])
                    ->add('email', EmailType::class, [
                        'required' => true
                    ])
                    ->add('phoneNumbers', PhoneNumberListType::class, [
                        'required' => true
                    ])
                ->end()
                ->with('Profesión', ['class' => 'col-md-4'])
                    ->add('job', JobType::class, [
                        'required' => true
                    ])
                ->end()
            ->end()
            ->with('Familiares', ['tab' => true])
                ->add('relatives', RelativesType::class, [
                    'studentId' => $subject->getId()
                ])
                ->end()
           ->end()
        ;
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
