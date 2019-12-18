<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Course;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\CourseStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class CourseAdmin extends AbstractAdmin
{
    /**
     * @var CourseTools
     */
    private CourseTools $adminTools;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        CourseTools $adminTools
    )
    {

        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;

        $status = CourseStatus::ACTIVE();
        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];

    }

    /**
     * @return CourseTools
     */
    public function adminTools(): CourseTools
    {
        return $this->adminTools;
    }


    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->orderBy('p.timeTable.end', 'DESC');

        return new ProxyQuery($queryBuilder);
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);
        return $actions;
    }

    /**
     * @param Course $object
     * @return string
     */
    public function toString($object)
    {
        return $object->name();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit', 'create', 'delete', 'export']);

        $collection->add('report-info', $this->getRouterIdParameter() . '/reports/info');
        $collection->add('report-certificate', $this->getRouterIdParameter() . '/reports/certificate');
        $collection->add('report-mark', $this->getRouterIdParameter() . '/reports/mark');
        return $collection;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {

        $datagridMapper
            ->add('status', 'doctrine_orm_string', [
                'show_filter' => true,
                'advanced_filter' => false,
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'label' => 'Estado',
                    'choices' => array_flip(CourseStatus::getConstants()),
                    'placeholder' => 'Todos'
                ],
            ])
            ->add('name', null, [
                'advanced_filter' => false
            ]);
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

}
