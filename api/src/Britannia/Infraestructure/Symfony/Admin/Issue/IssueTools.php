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

namespace Britannia\Infraestructure\Symfony\Admin\Issue;


use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class IssueTools extends AdminTools
{
    /**
     * @var IssueMapper
     */
    private IssueMapper $mapper;

    public function __construct(IssueMapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function dataSource(DatagridInterface $listMapper): ?AdminDataSource
    {
        return null;
    }

    public function dataGrid(ListMapper $listMapper): ?IssueDatagrid
    {
        return IssueDatagrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): ?AdminForm
    {
        return IssueForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function filters(DatagridMapper $filterMapper): ?IssueFilter
    {
        return IssueFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): ?IssueRoutes
    {
        return IssueRoutes::make($collection, $idParameter);
    }
}
