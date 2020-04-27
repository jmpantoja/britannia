<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Notification;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class NotificationAdmin extends AbstractAdmin implements AdminFilterableInterface
{

    protected $datagridValues = [
        '_sort_by' => 'date',
        '_sort_order' => 'DESC',
    ];
    /**
     * @var NotificationTools
     */
    private NotificationTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct($code, $class, $baseControllerName, NotificationTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return NotificationTools
     */
    public function adminTools(): NotificationTools
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
        $collection->remove('create');
        return $collection;
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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $notification = $this->getSubject();

        $this->adminTools()
            ->form($formMapper)
            ->configure($notification);
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
