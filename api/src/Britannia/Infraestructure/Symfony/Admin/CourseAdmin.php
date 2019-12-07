<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Domain\VO\Course\Support\Support;
use Britannia\Infraestructure\Symfony\Form\Type\Course\AgeType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\DiscountListTye;
use Britannia\Infraestructure\Symfony\Form\Type\Course\CourseHasStudentsType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\ExaminerType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\IntensiveType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LevelType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PeriodicityType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\SupportType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TeachersType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TimeTable\TimeTableType;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CourseAdmin extends AbstractAdmin
{
    public function __construct(string $code, string $class, string $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        $status = CourseStatus::ACTIVE();

        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];
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

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit', 'create', 'delete', 'export']);

        $collection->add('report-info', $this->getRouterIdParameter() . '/reports/info');
        $collection->add('report-certificate', $this->getRouterIdParameter() . '/reports/certificate');
        $collection->add('report-mark', $this->getRouterIdParameter() . '/reports/mark');
        return $collection;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);
        return $actions;
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
            ->addIdentifier('fullName.lastName', 'string', [
                'template' => 'admin/course/course_list_field.html.twig',
                'label' => 'Cursos'
            ])
            ->add('status', null, [
                'header_style' => 'width:30px;',
                'template' => 'admin/course/status_list_field.html.twig',
                'row_align' => 'center'
            ])
            ->add('_action', null, [
                'label' => 'Informes',
                'header_style' => 'width:210px; text-align: center',
                'row_align' => 'center',
                'actions' => [
                    'report-info' => [
                        'template' => 'admin/course/course_info_report_action.html.twig'
                    ]
                ]
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        /** @var Course $course */
        $course = $this->getSubject();

        $formMapper
            ->with('Ficha del curso', ['tab' => true])
                ->with('Nombre', ['class' => 'col-md-12 horizontal'])
                    ->add('name', TextType::class, [
                        'required' => true
                    ])
                    ->add('schoolCourse')
                    ->add('numOfPlaces', PositiveIntegerType::class, [
                        'label' => 'Plazas',
                    ])
                ->end()
                ->with('Descripción', ['class' => 'col-md-12 horizontal'])
                    ->add('support', SupportType::class, [
                        'label' => '¿Es de apoyo?'
                    ])
                    ->add('periodicity', PeriodicityType::class, [
                        'label' => 'Periocidad'
                    ])
                    ->add('intensive', IntensiveType::class, [
                        'label' => '¿Es intensivo?'
                    ])
                    ->add('age', AgeType::class, [
                        'label' => 'Grupo de Edad'
                    ])
                ->end()
                ->with('Certificado', ['class' => 'col-md-12 horizontal'])
                    ->add('examiner', ExaminerType::class, [
                        'label' => 'Examinador'
                    ])
                    ->add('level', LevelType::class, [
                        'required' => false,
                        'label' => 'Nivel'
                    ])
                ->end()
            ->end()

            ->with('Coste', ['tab' => true])
                ->with('Coste', ['class' => 'col-md-6'])
                    ->add('enrolmentPayment', PriceType::class, [
                        'label' => 'Matrícula',
                    ])
                    ->add('monthlyPayment', PriceType::class, [
                        'label' => 'Mensualidad',
                    ])
                    ->add('books', null, [
                        'label' => 'Material'
                    ])
                ->end()
                ->with('Descuentos', ['class' => 'col-md-6'])
                    ->add('discount', DiscountListTye::class, [
                        'label' => false,
                    ])
                ->end()
            ->end()
            ->with('Fechas', ['tab' => true])
                ->with('Fechas', ['class' => 'col-md-6'])
                    ->add('timeTable', TimeTableType::class, [
                        'label' => false,
                        'course' => $course
                    ])
                ->end()
            ->end()
            ->with('Alumnos y profesores', ['tab' => true])
                ->with('Profesores', ['class' => 'col-md-5'])
                    ->add('teachers', TeachersType::class)
                ->end()
                ->with('Alumnos', ['class' => 'col-md-7'])
                    ->add('courseHasStudents', CourseHasStudentsType::class, [
                        'course' => $this->subject
                    ])
                ->end()
            ->end();
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name')
            ->add('examiner')
            ->add('age');
    }

    /**
     * @param Course $object
     * @return string
     */
    public function toString($object)
    {
        return $object->getName();
    }


}
