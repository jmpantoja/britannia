<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\CourseReport;

use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class CourseReportAdmin extends AbstractAdmin implements AdminFilterableInterface
{

    protected $baseRouteName = 'admin_britannia_domain_course_report';
    protected $baseRoutePattern = '/britannia/domain/course-report';

    /**
     * @var CourseReportTools
     */
    private CourseReportTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct($code, $class, $baseControllerName, CourseReportTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
        $this->dataGridValues();
    }

    /**
     * @return CourseReportTools
     */
    public function adminTools(): CourseReportTools
    {
        return $this->adminTools;
    }

    public function dataGridValues(): array
    {
        $status = CourseStatus::ACTIVE();
        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];

        return $this->datagridValues;
    }

    public function configureActionButtons($action, $object = null)
    {

        $actions = parent::configureActionButtons($action, $object);

        $actions['list_courses'] = [
            'template' => 'admin/course/list_courses_button.html.twig'
        ];

        return $actions;
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
