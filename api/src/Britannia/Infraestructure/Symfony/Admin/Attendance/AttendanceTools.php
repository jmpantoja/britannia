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

namespace Britannia\Infraestructure\Symfony\Admin\Attendance;


use Britannia\Domain\Entity\Lesson\Lesson;
use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Security\Core\Security;

final class AttendanceTools extends AdminTools
{

    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var AttendanceMapper
     */
    private AttendanceMapper $mapper;

    public function __construct(AttendanceMapper $mapper, Security $security)
    {
        $this->security = $security;
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): ?AdminDataGrid
    {
        return null;
    }

    public function form(FormMapper $formMapper): AttendanceForm
    {
        return AttendanceForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }

    public function query(ProxyQuery $query): AttendanceQuery
    {
        return AttendanceQuery::make($query)
            ->setUser($this->currentUser());
    }

    public function filters(DatagridMapper $filterMapper): AttendanceFilters
    {
        return AttendanceFilters::make($filterMapper)
            ->setUser($this->currentUser());
    }

    public function routes(RouteCollection $collection, string $idParameter): ?AdminRoutes
    {
        return null;
    }

    public function dataSource(DatagridInterface $dataGrid): ?AdminDataSource
    {
        return null;
    }

    public function isGranted($name, ?Lesson $lesson, bool $default)
    {
        $isGranted = $default;

        if ($name === 'EDIT') {
            $user = $this->currentUser();
            $isGranted = $user->isTeacherOfCourse($lesson->course());
        }

        return $isGranted;
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    protected function currentUser()
    {
        return $this->security->getUser();
    }
}
