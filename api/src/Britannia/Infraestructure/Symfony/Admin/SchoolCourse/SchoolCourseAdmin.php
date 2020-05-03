<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\SchoolCourse;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class SchoolCourseAdmin extends AbstractAdmin implements AdminFilterableInterface
{

    protected $datagridValues = [
        '_sort_by' => 'weight',
    ];

    protected $maxPerPage = 30;
    protected $maxPageLinks = 10;

    /**
     * @var SchoolCourseTools
     */
    private SchoolCourseTools $adminTools;

    public function __construct($code, $class, $baseControllerName, SchoolCourseTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }


    /**
     * @return SchoolCourseTools
     */
    public function adminTools(): SchoolCourseTools
    {
        return $this->adminTools;
    }


    public function getBatchActions()
    {
        return [];
    }

    public function dataGridValues(): array
    {
        return $this->datagridValues;
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
            ->configure();
    }

}
