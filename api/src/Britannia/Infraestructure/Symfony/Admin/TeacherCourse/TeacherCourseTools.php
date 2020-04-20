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

namespace Britannia\Infraestructure\Symfony\Admin\TeacherCourse;


use Britannia\Domain\Repository\LessonRepositoryInterface;
use Britannia\Infraestructure\Symfony\Service\Schedule\ScheduleService;
use Carbon\CarbonImmutable;
use Exception;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

final class TeacherCourseTools extends AdminTools
{

    /**
     * @var TeacherCourseMapper
     */
    private TeacherCourseMapper $mapper;

    /**
     * @var ScheduleService
     */
    private ScheduleService $scheduleService;
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var Request
     */
    private RequestStack $requestStack;
    /**
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;


    public function __construct(TeacherCourseMapper $mapper,
                                RequestStack $requestStack,
                                Security $security,
                                LessonRepositoryInterface $lessonRepository,
                                ScheduleService $scheduleService)
    {
        $this->mapper = $mapper;
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->lessonRepository = $lessonRepository;
        $this->scheduleService = $scheduleService;
    }

    public function dataGrid(ListMapper $listMapper): TeacherCourseDatagrid
    {
        return TeacherCourseDatagrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): TeacherCourseForm
    {
        $uniqId = $formMapper->getAdmin()->getUniqid();
        $date = $this->getDate($uniqId);

        return TeacherCourseForm::make($formMapper)
            ->setDate($date)
            ->setLessonRepository($this->lessonRepository)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): ?AdminQuery
    {
        return TeacherCourseQuery::make($query)
            ->setUser($this->security->getUser());
    }

    public function filters(DatagridMapper $filterMapper): TeacherCourseFilter
    {
        return TeacherCourseFilter::make($filterMapper);
    }

    public function routes(RouteCollection $collection, string $idParameter): TeacherCourseRoutes
    {
        return TeacherCourseRoutes::make($collection, $idParameter);

    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return TeacherCourseDataSource::make($dataGrid)->setScheduleService($this->scheduleService);
    }

    private function getDate(string $uniqId): CarbonImmutable
    {
        try {
            $date = $this->requestStack->getCurrentRequest()->get('date');
            if (empty($date)) {
                return CarbonImmutable::today();
            }

            return CarbonImmutable::parse($date);
        } catch (Exception $exception) {
            throw new Exception('El formato del parámetro fecha no es correcto');
        }
    }
}
