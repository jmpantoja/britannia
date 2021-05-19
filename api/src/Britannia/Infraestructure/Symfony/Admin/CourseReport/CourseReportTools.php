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

namespace Britannia\Infraestructure\Symfony\Admin\CourseReport;


use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class CourseReportTools extends AdminTools
{

    /**
     * @var CourseReportMapper
     */
    private CourseReportMapper $mapper;

    public function __construct(CourseReportMapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function dataGrid(ListMapper $listMapper): ?CourseReportDataGrid
    {
        return CourseReportDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): ?CourseReportForm
    {
        return CourseReportForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?CourseReportQuery
    {
        return CourseReportQuery::make($query);
    }

    public function filters(DatagridMapper $filterMapper): ?CourseReportFilter
    {
        return CourseReportFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?CourseReportRoutes
    {
        return CourseReportRoutes::make($collection, $idParameter);
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return null;
    }
}
