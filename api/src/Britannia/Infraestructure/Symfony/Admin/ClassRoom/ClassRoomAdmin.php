<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\ClassRoom;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class ClassRoomAdmin extends AbstractAdmin implements AdminFilterableInterface
{

    protected $datagridValues = [
        '_sort_by' => 'name',
    ];
    /**
     * @var ClassRoomTools
     */
    private ClassRoomTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;


    public function __construct($code, $class, $baseControllerName, ClassRoomTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return ClassRoomTools
     */
    public function adminTools(): ClassRoomTools
    {
        return $this->adminTools;
    }

    public function getBatchActions()
    {
        return [];
    }

    public function dataGridValues(): array
    {
        return $this->datagridValues;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(AdminRoutes::ROUTE_LIST);
       $collection->remove('delete');
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

    public function getDataSourceIterator()
    {

        return $this->adminTools()
            ->dataSource($this->getDatagrid())
            ->build();
    }
}
