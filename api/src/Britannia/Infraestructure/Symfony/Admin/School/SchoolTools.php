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

namespace Britannia\Infraestructure\Symfony\Admin\School;


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

final class SchoolTools extends AdminTools
{

    /**
     * @var SchoolMapper
     */
    private SchoolMapper $mapper;

    public function __construct(SchoolMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): SchoolDataGrid
    {
        return SchoolDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): SchoolForm
    {
        return SchoolForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function filters(DatagridMapper $filterMapper): SchoolFilter
    {
        return SchoolFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return null;
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return SchoolDataSource::make($dataGrid);
    }
}
