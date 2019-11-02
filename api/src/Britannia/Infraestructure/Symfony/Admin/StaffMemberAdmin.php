<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Form\RoleType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Security;

final class StaffMemberAdmin extends AbstractAdmin
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(string $code, string $class, string $baseControllerName, Security $security)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->security = $security;
    }

    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->orderBy('p.active', 'DESC')
            ->orderBy('p.fullName.lastName', 'ASC')
        ;

        return new ProxyQuery($queryBuilder);
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
            ->addIdentifier('fullName.lastName', 'string', [
                'template' => 'admin/staff/staff_resume_column.html.twig',
                'label' => 'Alumnos'
            ])
            ->add('active', null, [
                'header_style' => 'width:30px',
                'row_align' => 'center'
            ]);

    }

    protected function configureFormFields(FormMapper $formMapper): void
    {

        $atCreated = (bool)$this->isCurrentRoute('create');
        $subject = $this->getSubject();

        $hasAccess = parent::hasAccess('edit', $subject);


        $formMapper
            ->with('Personal', ['tab' => true])
            ->with('Acceso', ['class' => 'col-md-3'])
                ->add('userName')
                ->add('plain_password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'required' => $atCreated,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                ])
            ->ifTrue($hasAccess)
                ->add('roles', RoleType::class)
            ->ifEnd()
        ->end();

        $formMapper
            ->with('Contacto', ['class' => 'col-md-5'])
                ->add('fullName', FullNameType::class)
                ->add('address', PostalAddressType::class, [
                    'required' => false
                ])
                ->add('emails', EmailListType::class)
                ->add('phoneNumbers', PhoneNumberListType::class)
        ->end();

        if($hasAccess && $subject->isTeacher()){
            $formMapper->with('Cursos', ['class' => 'col-md-4'])
                ->add('courses')
                ->end();
        }

        $formMapper->end();
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

    public function hasAccess($action, $object = null)
    {
        $currentUser = $this->security->getUser();

        if ($action === 'edit' && $currentUser instanceof StaffMember && $currentUser->isEqual($object)) {
            return true;
        }


        return parent::hasAccess($action, $object);
    }


    public function checkAccess($action, $object = null)
    {
        if ($this->hasAccess($action, $object)) {
            return;
        }

        parent::checkAccess($action, $object); // TODO: Change the autogenerated stub
    }

}
