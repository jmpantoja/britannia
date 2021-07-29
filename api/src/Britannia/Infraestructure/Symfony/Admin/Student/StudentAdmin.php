<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Student;

use Britannia\Domain\Entity\Student\StudentHasBeenCreated;
use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use PlanB\DDD\Domain\Event\DomainEvent;
use PlanB\DDD\Domain\Event\EventDispatcher;
use PlanB\DDDBundle\Symfony\Form\Type\DateType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class  StudentAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    protected $datagridValues = [
        'active' => ['value' => true],
    ];

    protected $maxPerPage = 30;
    protected $maxPageLinks = 10;
    /**
     * @var StudentTools
     */
    private StudentTools $adminTools;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                StudentTools $adminTools
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return StudentTools
     */
    public function adminTools(): StudentTools
    {
        return $this->adminTools;
    }

    /**
     * @return array
     */
    public function datagridValues(): array
    {
        return $this->datagridValues;
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

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        return $this->adminTools()
            ->query($query)
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
        $student = $this->getSubject();

        $this->adminTools()
            ->form($formMapper)
            ->configure($student);
    }

    public function getDataSourceIterator()
    {
        return $this->adminTools()
            ->dataSource($this->getDatagrid())
            ->build();

    }

    public function configureActionButtons($action, $object = null)
    {
        $list = [];

        $list['issues']['template'] = 'admin/student/student_go_to_issues.html.twig';
        $list = array_merge($list, parent::configureActionButtons($action, $object));

        return $list;
    }
}
