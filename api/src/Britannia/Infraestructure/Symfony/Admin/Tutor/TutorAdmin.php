<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Tutor;

use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
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
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class TutorAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_sort_by' => 'fullName.lastName',
    ];
    /**
     * @var TutorTools
     */
    private TutorTools $adminTools;

    public function __construct($code, $class, $baseControllerName, TutorTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return TutorTools
     */
    public function adminTools(): TutorTools
    {
        return $this->adminTools;
    }

    public function getBatchActions()
    {
        return [];
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(AdminRoutes::ROUTE_LIST);
        return $collection;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->adminTools()
            ->filters($datagridMapper)
            ->configure();
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->adminTools()
            ->dataGrid($listMapper)
            ->configure();
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->adminTools()
            ->form($formMapper)
            ->configure();
    }
}
