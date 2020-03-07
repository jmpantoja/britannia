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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Infraestructure\Symfony\Service\Schedule\ScheduleService;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class CourseTools extends AdminTools
{

    /**
     * @var CourseMapper
     */
    private CourseMapper $mapper;

    /**
     * @var Setting
     */
    private Setting $setting;
    /**
     * @var ScheduleService
     */
    private ScheduleService $scheduleService;


    public function __construct(CourseMapper $mapper,
                                Setting $setting,
                                ScheduleService $scheduleService)
    {
        $this->mapper = $mapper;
        $this->setting = $setting;
        $this->scheduleService = $scheduleService;

    }

    public function dataGrid(ListMapper $listMapper): CourseDatagrid
    {
        return CourseDatagrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): CourseForm
    {
        return CourseForm::make($formMapper)
            ->setSettings($this->setting)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return CourseQuery::make($query);
    }

    public function filters(DatagridMapper $filterMapper): CourseFilter
    {
        return CourseFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): CourseRoutes
    {
        return CourseRoutes::make($collection, $idParameter);

    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return CourseDataSource::make($dataGrid)->setScheduleService($this->scheduleService);
    }
}
