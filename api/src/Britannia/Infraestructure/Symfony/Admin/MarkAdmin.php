<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\CourseStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MarkAdmin extends AbstractAdmin implements DataMapperInterface
{
    protected $baseRouteName = 'admin_britannia_domain_course_mark';
    protected $baseRoutePattern = '/britannia/domain/course-mark';

    public function __construct(string $code, string $class, string $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        $status = CourseStatus::ACTIVE();

        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);
        return $actions;
    }

    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->where('p.numOfUnits > 0')
            ->orderBy('p.timeTable.end', 'DESC');

        return new ProxyQuery($queryBuilder);
    }

    public function mapDataToForms($viewData, $forms)
    {
//        if (is_null($viewData)) {
//            dump($forms);
//            die('2aaa');
//            return;
//        }
//
//        dump($viewData);
//        die('xxx');
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        dump($forms['mark_0']->getData());
        die();

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);

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
        $listMapper
            ->addIdentifier('name', 'string', [
                'template' => 'admin/course/course_list_field.html.twig',
                'label' => 'Cursos'
            ])
            ->add('status', null, [
                'header_style' => 'width:30px;',
                'template' => 'admin/course/status_list_field.html.twig',
                'row_align' => 'center'
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        /** @var Course $course */
        $course = $this->getSubject();

        if (!$course->hasUnits()) {
            $message = sprintf('no se puede editar las calificaciones de un curso sin unidades definidas');
            throw new NotFoundHttpException($message);
        }

        $units = $course->getUnits();

        dump($units, $course->hasUnits());
        die();

        $formMapper->getFormBuilder()->setDataMapper($this);

        foreach ($units as $key => $unit) {
            $formMapper->with($unit->getLabel(), ['tab' => true])
                ->with($unit->getName())
                ->add('mark_' . $key, TextType::class, [
                    'by_reference' => true,
                    'mapped' => false
                ])
                ->end()
                ->end();
        }
    }
}
