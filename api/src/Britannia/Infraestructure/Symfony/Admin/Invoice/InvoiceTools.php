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

namespace Britannia\Infraestructure\Symfony\Admin\Invoice;


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

final class InvoiceTools extends AdminTools
{

    /**
     * @var InvoiceMapper
     */
    private InvoiceMapper $mapper;

    public function __construct(InvoiceMapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function dataGrid(ListMapper $listMapper): InvoiceDataGrid
    {
        return InvoiceDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): ?AdminForm
    {
        return InvoiceForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function filters(DatagridMapper $filterMapper): ?InvoiceFilter
    {
        return InvoiceFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return InvoiceRoutes::make($collection, $idParameter);
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return InvoiceDataSource::make($dataGrid);
    }
}
