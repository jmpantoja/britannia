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

namespace Britannia\Infraestructure\Symfony\Admin\Academy;


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Infraestructure\Symfony\Admin\Course\CourseMapper;
use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;
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

final class AcademyTools extends AdminTools
{

    /**
     * @var AcademyMapper
     */
    private AcademyMapper $mapper;

    public function __construct(AcademyMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): AcademyDataGrid
    {
        return AcademyDataGrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): AcademyForm
    {
        return AcademyForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function filters(DatagridMapper $filterMapper): AcademyFilters
    {
        return AcademyFilters::make($filterMapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return null;
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return null;
    }

    public function dataSource(DatagridInterface $datagrid): ?AdminDataSource
    {
        return AcademyDataSource::make($datagrid);
    }
}
