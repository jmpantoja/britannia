<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Student\Adult;
use Britannia\Infraestructure\Symfony\Form\JobType;
use Britannia\Infraestructure\Symfony\Form\PaymentType;
use Britannia\Infraestructure\Symfony\Form\RelativesType;
use PlanB\DDD\Domain\VO\Validator\DNI;
use PlanB\DDDBundle\Symfony\Form\Type\DateType;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;

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
            ->with('Pago', ['tab' => true])
                ->with('Descuento', ['class' => 'col-md-4'])
                    ->add('relatives', RelativesType::class, [
                        'label' => 'Familiares',
                        'studentId' => $subject->getId()
                    ])
                ->end()
                ->with('Forma de pago', ['class'=>'col-md-8'])
//                    ->add('payment', PaymentType::class, [
//                        'label' => false,
//                        'required' => true
//                    ])
                ->end()
            ->end()

            ->with('Personal', ['tab' => true])
                ->with('Datos personales', ['class' => 'col-md-4'])
                    ->add('fullName', FullNameType::class, [
                        'sonata_help'=> 'Escriba el nombre y apellidos del alumno',
                        'label' => 'Nombre y apellidos',
                        'required' => true
                    ])
                    ->add('birthDate', DatePickerType::class, [
                        'label' => 'Fecha de nacimiento',
                        'required' => true,
                        'format' => \IntlDateFormatter::LONG,
                        'empty_data' => "\r"
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
                        'sonata_help'=>'Escriba una dirección postal',
                        'label' => 'Dirección Postal',
                        'required' => true,
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
                        'sonata_help'=>'Escriba una ocupación y una situación laboral',
                        'required' => false,
                        'label'=>'Ocupación'
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
