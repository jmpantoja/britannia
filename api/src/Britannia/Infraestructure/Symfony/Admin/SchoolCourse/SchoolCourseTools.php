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

namespace Britannia\Infraestructure\Symfony\Admin\SchoolCourse;


use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class SchoolCourseTools extends AdminTools
{

    /**
     * @var SchoolCourseMapper
     */
    private SchoolCourseMapper $mapper;

    public function __construct(SchoolCourseMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): SchoolCourseDataGrid
    {
        return SchoolCourseDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): SchoolCourseForm
    {

        return SchoolCourseForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function filters(DatagridMapper $filterMapper): ?SchoolCourserFilter
    {
        return SchoolCourserFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return null;
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return null;
        return SchoolDataSource::make($dataGrid);
    }
}
