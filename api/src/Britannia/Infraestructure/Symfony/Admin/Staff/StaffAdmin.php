<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Staff;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Exporter\Source\ArraySourceIterator;

final class StaffAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    /**
     * @var StaffTools
     */
    private StaffTools $staffTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                StaffTools $staffTools)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->staffTools = $staffTools;
    }

    protected function adminTools(): StaffTools
    {
        return $this->staffTools;
    }


    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        return $this->adminTools()
            ->query($query)
            ->build();
    }

    public function getBatchActions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function datagridValues(): array
    {
        return $this->datagridValues;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        return $this->adminTools()
            ->routes($collection, $this->getRouterIdParameter())
            ->build();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->adminTools()
            ->filters($datagridMapper)
            ->configure();
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->staffTools
            ->dataGrid($listMapper)
            ->configure();
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {

        $staffMember = $this->getSubject();
        $hasRootPrivileges = parent::hasAccess('edit', $staffMember);

        $this->staffTools
            ->form($formMapper)
            ->configure($staffMember, $hasRootPrivileges);

        return;
    }

    public function getDataSourceIterator()
    {

        return $this->adminTools()
            ->dataSource($this->getDatagrid())
            ->build();

    }

    public function toString($object)
    {
        return $object->fullName();
    }
}
