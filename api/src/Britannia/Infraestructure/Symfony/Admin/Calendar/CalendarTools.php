<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Calendar;


use Britannia\Domain\Repository\CalendarRepositoryInterface;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class CalendarTools extends AdminTools
{
    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $repository;

    public function __construct(CalendarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    public function dataGrid(ListMapper $listMapper): CalendarDataGrid
    {
        return CalendarDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): ?AdminForm
    {
        return null;
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function filters(DatagridMapper $filterMapper): CalendarFilters
    {
        return CalendarFilters::make($filterMapper)
            ->setRepository($this->repository);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return CalendarRoutes::make($collection, $idParameter);
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return null;
    }
}
