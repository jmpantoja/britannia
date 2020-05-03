<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\MessageTemplate;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

final class TemplateAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    /**
     * @var TemplateTools
     */
    private TemplateTools $adminTools;

    protected $maxPerPage = 30;
    protected $maxPageLinks = 10;

    public function __construct($code, $class, $baseControllerName, TemplateTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @inheritDoc
     */
    public function datagridValues(): array
    {
        return $this->datagridValues;
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

    /**
     * @return TemplateTools
     */
    public function adminTools(): TemplateTools
    {
        return $this->adminTools;
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
            ->configure($this->getSubject());
    }

}
