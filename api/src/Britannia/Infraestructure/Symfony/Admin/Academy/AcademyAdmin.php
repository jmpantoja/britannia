<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Academy;

use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class AcademyAdmin extends AbstractAdmin
{
    /**
     * @var AcademyTools
     */
    private AcademyTools $academyTools;

    protected $datagridValues = [
        '_sort_by' => 'fullName.lastName',
    ];


    public function __construct($code, $class, $baseControllerName, AcademyTools $academyTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->academyTools = $academyTools;
    }

    protected function adminTools(): AcademyTools
    {
        return $this->academyTools;
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

