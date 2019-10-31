<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\JobType;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TutorAdmin extends AbstractAdmin
{

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('dni')
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName')
            ->add('address.address')
            ->add('address.postalCode')
            ->add('job.name')
            ->add('job.status')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper

            ->addIdentifier('id', 'string', [
                'template' => 'admin/student/tutor_list_field.html.twig',
                'label' => 'Tutor'
            ]);

    }

    protected function configureFormFields(FormMapper $formMapper): void
    {

        $formMapper
            ->with('Personal', ['tab' => true])
                ->with('Datos personales', ['class' => 'col-md-4'])
                    ->add('fullName', FullNameType::class, [
                        'sonata_help'=> 'Escriba el nombre y apellidos del alumno',
                        'label' => 'Nombre y apellidos',
                        'required' => true
                    ])
                    ->add('dni', DNIType::class, [
                        'empty_data' => null,
                        'required' => false
                    ])
                ->end()
                ->with('Contacto', ['class' => 'col-md-4'])
                    ->add('address', PostalAddressType::class, [
                        'sonata_help'=>'Escriba una dirección postal',
                        'label' => 'Dirección Postal',
                        'required' => false,
                    ])
                    ->add('emails', EmailListType::class, [
                        'required' => true,
                        'label' => 'Correos Electrónicos',
                    ])
                    ->add('phoneNumbers', PhoneNumberListType::class, [
                        'required' => false,
                        'label' => 'Números de teléfono',
                    ])
                ->end()
                ->with( 'Profesión' , ['class' => 'col-md-4'])
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
            ->add('dni')
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName')
            ->add('address.address')
            ->add('address.postalCode')
            ->add('job.name')
            ->add('job.status')
            ;
    }

}
