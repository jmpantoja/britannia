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

namespace Britannia\Infraestructure\Symfony\Admin\Staff;


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
use Symfony\Component\Security\Core\Security;

final class StaffTools extends AdminTools
{
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var StaffMapper
     */
    private StaffMapper $mapper;

    public function __construct(Security $security, StaffMapper $mapper)
    {
        $this->security = $security;
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): StaffDatagrid
    {
        return StaffDatagrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): StaffForm
    {
        return StaffForm::make($formMapper, $this->security)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return StaffQuery::make($query);
    }


    public function filters(DatagridMapper $filterMapper): ?StaffFilters
    {
        return StaffFilters::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return StaffRoutes::make($collection, $idParameter);
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return StaffDataSource::make($dataGrid);
    }
}
