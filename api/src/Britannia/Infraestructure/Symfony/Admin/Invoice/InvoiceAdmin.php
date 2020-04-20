<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Invoice;

use Britannia\Domain\Repository\LessonRepositoryInterface;
use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class InvoiceAdmin extends AbstractAdmin implements AdminFilterableInterface
{

    protected $datagridValues = [
        '_sort_by' => 'createdAt',
        '_sort_order' => 'DESC',

    ];
    /**
     * @var InvoiceTools
     */
    private InvoiceTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;
    /**
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;

    public function __construct($code,
                                $class,
                                $baseControllerName,
                                LessonRepositoryInterface $lessonRepository,
                                InvoiceTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->lessonRepository = $lessonRepository;
        $this->adminTools = $adminTools;
    }

    /**
     * @return InvoiceTools
     */
    public function adminTools(): InvoiceTools
    {
        return $this->adminTools;
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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $notification = $this->getSubject();

        $this->adminTools()
            ->form($formMapper)
            ->configure($notification);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->adminTools()
            ->dataGrid($listMapper)
            ->configure();
    }

    public function getDataSourceIterator()
    {

        return $this->adminTools()
            ->dataSource($this->getDatagrid())
            ->build();

    }

}
