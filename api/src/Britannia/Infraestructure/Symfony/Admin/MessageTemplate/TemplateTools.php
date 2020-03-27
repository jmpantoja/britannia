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

namespace Britannia\Infraestructure\Symfony\Admin\MessageTemplate;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
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

final class TemplateTools extends AdminTools
{
    /**
     * @var TemplateMapper
     */
    private TemplateMapper $mapper;


    /**
     * TemplateTools constructor.
     */
    public function __construct(TemplateMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function dataSource(DatagridInterface $listMapper): ?AdminDataSource
    {
        return null;
    }

    public function dataGrid(ListMapper $listMapper): ?TemplateDataGrid
    {
        return TemplateDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): ?TemplateForm
    {
        return TemplateForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function filters(DatagridMapper $filterMapper): ?TemplateFilter
    {
        return TemplateFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return null;
    }
}
