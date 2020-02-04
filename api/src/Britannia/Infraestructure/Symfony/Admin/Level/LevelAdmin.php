<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Level;

use Britannia\Infraestructure\Symfony\Form\ExaminerType;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class LevelAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_sort_by' => 'name',
    ];
    /**
     * @var LevelTools
     */
    private LevelTools $adminTools;

    public function __construct($code, $class, $baseControllerName, LevelTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return LevelTools
     */
    public function adminTools(): LevelTools
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
