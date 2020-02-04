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

namespace Britannia\Infraestructure\Symfony\Admin\Report;


use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class ReportTools extends AdminTools
{

    /**
     * @var ReportMapper
     */
    private ReportMapper $mapper;

    public function __construct(ReportMapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function dataGrid(ListMapper $listMapper): ?ReportDataGrid
    {
        return ReportDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): ?ReportForm
    {
        return ReportForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?ReportQuery
    {
        return ReportQuery::make($query);
    }

    public function filters(DatagridMapper $filterMapper): ?ReportFilter
    {
        return ReportFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?ReportRoutes
    {
        return ReportRoutes::make($collection, $idParameter);
    }
}
