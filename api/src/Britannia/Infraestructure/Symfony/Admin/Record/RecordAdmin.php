<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Record;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class RecordAdmin extends AbstractAdmin implements AdminFilterableInterface
{

    protected $datagridValues = [
        '_sort_by' => 'date',
        '_sort_order' => 'DESC',
    ];
    /**
     * @var RecordTools
     */
    private RecordTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct($code, $class, $baseControllerName, RecordTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return RecordTools
     */
    public function adminTools(): RecordTools
    {
        return $this->adminTools;
    }

    public function getBatchActions()
    {
        return [];
    }

    protected function configureRoutes(RouteCollection $collection)
    {
//        dumps($collection->getElements());
//        die();
        $collection->clearExcept(['list', 'export']);
        parent::configureRoutes($collection);
    }


    public function dataGridValues(): array
    {
        return $this->datagridValues;
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

    public function getDataSourceIterator()
    {

        return $this->adminTools()
            ->dataSource($this->getDatagrid())
            ->build();

    }

}
