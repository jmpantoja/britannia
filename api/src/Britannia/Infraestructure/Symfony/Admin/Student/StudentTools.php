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

namespace Britannia\Infraestructure\Symfony\Admin\Student;


use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class StudentTools extends AdminTools
{

    /**
     * @var StudentMapper
     */
    private StudentMapper $mapper;

    public function __construct(StudentMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): StudentDataGrid
    {
        return StudentDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): StudentForm
    {
        return StudentForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): StudentQuery
    {
        return StudentQuery::make($query);
    }

    public function filters(DatagridMapper $filterMapper): StudentFilter
    {
        return StudentFilter::make($filterMapper);
    }


    public function routes(RouteCollection $collection, string $idParameter): StudentRoutes
    {
        return StudentRoutes::make($collection, $idParameter);

    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return StudentDataSource::make($dataGrid);
    }

}
