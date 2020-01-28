<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Report;

use Britannia\Domain\VO\Course\CourseStatus;
use PlanB\DDD\Domain\VO\PositiveInteger;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class ReportAdmin extends AbstractAdmin
{

    protected $baseRouteName = 'admin_britannia_domain_course_report';
    protected $baseRoutePattern = '/britannia/domain/course-report';

    /**
     * @var ReportTools
     */
    private ReportTools $adminTools;

    public function __construct($code, $class, $baseControllerName, ReportTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
        $this->dataGridValues();
    }

    /**
     * @return ReportTools
     */
    public function adminTools(): ReportTools
    {
        return $this->adminTools;
    }

    protected function dataGridValues(): void
    {
        $status = CourseStatus::ACTIVE();
        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];
    }

    public function getBatchActions()
    {
        return [];
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        return $this->adminTools()
            ->query($query)
            ->build();

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection = $this->adminTools()
            ->routes($collection, $this->getRouterIdParameter())
            ->build();

        return $collection;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->adminTools()
            ->filters($datagridMapper)
            ->configure();
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->adminTools()
            ->dataGrid($listMapper)
            ->configure();
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {


        $course = $this->getSubject();
        $this->adminTools()
            ->form($formMapper)
            ->configure($course);
    }


    public function update($object)
    {
        return $object;
    }


}
