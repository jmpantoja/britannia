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

namespace PlanB\DDDBundle\Sonata\Admin;


use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

abstract class AdminTools
{
    abstract public function dataSource(DatagridInterface $listMapper): ?AdminDataSource;

    abstract public function dataGrid(ListMapper $listMapper): ?AdminDataGrid;

    abstract public function form(FormMapper $formMapper): ?AdminForm;

    abstract public function query(ProxyQuery $query): ?AdminQuery;

    abstract public function filters(DatagridMapper $filterMapper): ?AdminFilter;

    abstract public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes;
}
