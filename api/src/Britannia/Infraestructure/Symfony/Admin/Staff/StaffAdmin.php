<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Staff;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class StaffAdmin extends AbstractAdmin
{
    /**
     * @var StaffTools
     */
    private StaffTools $staffTools;

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


    protected function configureRoutes(RouteCollection $collection)
    {
        return $this->adminTools()
            ->routes($collection, $this->getIdParameter())
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

    public function checkAccess($action, $object = null)
    {
        if (!$this->hasAccess($action, $object)) {
            parent::checkAccess($action, $object);
        }
    }

    public function hasAccess($action, $object = null): bool
    {
        $isEditAllowed = $this->staffTools
            ->security()
            ->isAllowed($action, $object);

        if ($isEditAllowed) {
            return true;
        }

        return parent::hasAccess($action, $object);
    }
}
