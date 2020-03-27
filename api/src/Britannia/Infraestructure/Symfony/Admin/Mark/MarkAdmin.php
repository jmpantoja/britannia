<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Mark;

use Britannia\Domain\Entity\Unit\Unit;
use Britannia\Domain\VO\Course\CourseStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;


final class MarkAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'admin_britannia_domain_course_mark';
    protected $baseRoutePattern = '/britannia/domain/course-mark';

    /**
     * @var MarkTools
     */
    private MarkTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                MarkTools $adminTools
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
        $this->dataGridValues();
    }

    /**
     * @return MarkTools
     */
    public function adminTools(): MarkTools
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
        $original = $this->getConfigurationPool()->getAdminByAdminCode('admin.course');


        return $this->adminTools()
            ->routes($collection, $this->getRouterIdParameter())
            ->build();
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
}
