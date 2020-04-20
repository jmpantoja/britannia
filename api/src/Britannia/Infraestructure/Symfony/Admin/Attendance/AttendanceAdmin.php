<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Attendance;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Carbon\Carbon;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class AttendanceAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    /**
     * @var AttendanceTools
     */
    private AttendanceTools $adminTools;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                AttendanceTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->dataGridValues();
        $this->adminTools = $adminTools;
    }

    /**
     * @return AttendanceTools
     */
    public function adminTools(): AttendanceTools
    {
        return $this->adminTools;
    }

    /**
     * @return array
     */
    public function datagridValues(): array
    {
        $today = Carbon::now();

        $this->datagridValues = [
            'day' => ['value' => \IntlDateFormatter::formatObject($today, "d MMM Y")]
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

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
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
        $this->setListMode('mosaic');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $lesson = $this->getSubject();

        $this->adminTools()
            ->form($formMapper)
            ->configure($lesson);
    }

    public function isGranted($name, $object = null)
    {
        $default = parent::isGranted($name, $object);

        return $this->adminTools()
            ->isGranted($name, $object, $default);

    }

}
