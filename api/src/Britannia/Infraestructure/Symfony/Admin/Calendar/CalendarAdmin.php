<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Calendar;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Carbon\CarbonImmutable;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class CalendarAdmin extends AbstractAdmin //implements AdminFilterableInterface
{
    /**
     * @var CalendarTools
     */
    private CalendarTools $adminTools;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                CalendarTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->dataGridValues();
        $this->adminTools = $adminTools;
    }

    /**
     * @return CalendarTools
     */
    public function adminTools(): CalendarTools
    {
        return $this->adminTools;
    }

    public function dataGridValues(): array
    {
        $today = CarbonImmutable::now();

        $this->datagridValues = [
            'month' => array('value' => $today->format('m')),
            'year' => array('value' => $today->format('Y'))
        ];

        return $this->datagridValues;
    }

    public function configureActionButtons($action, $object = null)
    {
        $actionButtons = parent::configureActionButtons($action, $object);
        unset($actionButtons['create']);
        return $actionButtons;
    }

    protected function configureBatchActions($actions)
    {
        if ($this->hasAccess('edit')) {
            $actions['to-holiday'] = ['label' => 'A Festivo', 'ask_confirmation' => false];
            $actions['to-workday'] = ['label' => 'A Laborable', 'ask_confirmation' => false];
        }

        return $actions;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'batch']);
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

}
