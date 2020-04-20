<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\TeacherCourse;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as RoutingUrlGeneratorInterface;

final class TeacherCourseAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    protected $baseRouteName = 'admin_britannia_domain_teacher';
    protected $baseRoutePattern = '/britannia/domain/teacher';

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'timeRange.start',
    ];

    /**
     * @var TeacherCourseTools
     */
    private TeacherCourseTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        TeacherCourseTools $adminTools
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
        $this->dataGridValues();
    }

    /**
     * @return TeacherCourseTools
     */
    public function adminTools(): TeacherCourseTools
    {
        return $this->adminTools;
    }

    /**
     * @return array
     */
    public function datagridValues(): array
    {
        $status = CourseStatus::ACTIVE();
        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];

        return $this->datagridValues;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        return $this->adminTools()
            ->query($query)
            ->build();
    }

    public function getBatchActions()
    {
        return [];
    }

    protected function configureRoutes(RouteCollection $collection)
    {
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
        $this->adminTools()
            ->form($formMapper)
            ->configure($this->getSubject());
    }


    public function getDataSourceIterator()
    {
        return $this->adminTools()
            ->dataSource($this->getDatagrid())
            ->build();

    }

    /**
     * @param Course $object
     * @return string
     */
    public function toString($object)
    {
        return $object->name();
    }

    public function generateObjectUrl($name, $object, array $parameters = [], $absolute = RoutingUrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if ('edit' === $name) {
            $date = $this->request->get('date');
            $parameters['date'] = $date;
            $parameters = array_filter($parameters);
        }

        return parent::generateObjectUrl($name, $object, $parameters, $absolute);
    }

    public function isGranted($name, $object = null)
    {
        if (!in_array($name, ['EDIT', 'LIST'])) {
            return false;
        }

        return parent::isGranted($name, $object);
    }


}
