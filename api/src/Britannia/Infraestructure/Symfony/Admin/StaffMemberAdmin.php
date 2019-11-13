<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Form\RoleType;
use Doctrine\ORM\EntityRepository;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
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
            ->addOrderBy('p.fullName.lastName', 'ASC');

        return new ProxyQuery($queryBuilder);
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);
        return $actions;
    }

    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->clearExcept(['list', 'edit', 'create', 'delete', 'export']);
        return $collection;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('active', null, [
                'label' => 'Activo'
            ])
            ->add('teacher', null, [
                'label' => 'Profesor'
            ])
            ->add('fullName', 'doctrine_orm_callback', [
                'label' => 'Nombre',
                'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                    if (!$value['value']) {
                        return;
                    }

                    $where = sprintf('%s.fullName.firstName like :name OR %s.fullName.lastName like :name', $alias, $alias);
                    $queryBuilder
                        ->andwhere($where)
                        ->setParameter('name', sprintf('%%%s%%', $value['value']));
                    return true;
                }
            ]);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {

        $listMapper
            ->addIdentifier('username', 'string', [
                'template' => 'admin/staff/staff_resume_column.html.twig',
                'label' => 'Alumnos'
            ])
            ->add('fullName', null, [
                'header_style' => 'width:300px',
                'row_align' => 'left'
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
            ->with('Personal', ['tab' => true]);

        if ($hasAccess && $subject->isTeacher()) {
            $formMapper->with('Cursos en Activo', ['class' => 'col-md-12'])
                ->add('courses', null, [
                    'label' => false,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->where('m.active = :param')
                            ->setParameter('param', true);
                    }
                ])
                ->end();
        }

        $formMapper->with('Acceso', ['class' => 'col-md-3'])
            ->add('userName')
            ->add('plain_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => $atCreated,
                'first_options' => ['label' => 'Password',
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ]],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->ifTrue($hasAccess)
            ->add('roles', RoleType::class)
            ->ifEnd()
            ->end();

        $formMapper
            ->with('Personal', ['class' => 'col-md-5'])
            ->add('fullName', FullNameType::class)
            ->add('address', PostalAddressType::class, [
                'required' => false
            ])
            ->add('dni', DNIType::class, [
                'required' => false
            ])
            ->end();

        $formMapper
            ->with('Contacto', ['class' => 'col-md-4'])
            ->add('emails', EmailListType::class)
            ->add('phoneNumbers', PhoneNumberListType::class)
            ->end();

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

        parent::checkAccess($action, $object);
    }

}
