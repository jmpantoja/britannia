<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Infraestructure\Symfony\Form\AgeType;
use Britannia\Infraestructure\Symfony\Form\ExaminerType;
use Britannia\Infraestructure\Symfony\Form\HoursPerWeekType;
use Britannia\Infraestructure\Symfony\Form\IntensiveType;
use Britannia\Infraestructure\Symfony\Form\LevelType;
use Britannia\Infraestructure\Symfony\Form\TimeSheetListType;
use Britannia\Infraestructure\Symfony\Form\PeriodicityType;
use Doctrine\ORM\EntityRepository;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CourseAdmin extends AbstractAdmin
{

    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->orderBy('p.endDate', 'DESC')
        ;

        return new ProxyQuery($queryBuilder);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit', 'create', 'delete', 'export']);
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
            ->add('active')
            ->add('name');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('fullName.lastName', 'string', [
                'template' => 'admin/course/course_list_field.html.twig',
                'label' => 'Cursos'
            ])
            ->add('active', null, [
                'header_style' => 'width:30px',
                'row_align' => 'center'
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        /** @var Course $course */
        $course = $this->getSubject();

        $isFinished = !$course->isActive();
        $isStarted = $course->isStarted();


        $formMapper
            ->with('Ficha del curso', ['tab'=>true])
                ->with('Nombre', ['class' => 'col-md-3'])
                    ->add('name', null, [
                        'attr'=>[
                            'style' => 'width:300px'
                        ]
                    ])
                ->end()

                ->with('Curso', ['class' => 'col-md-3' ])
                    ->add('schoolCourse', null, [
                        'attr'=>[
                            'style' => 'width:250px'
                        ]
                    ])
                ->end()

                ->with('Examinador', ['class' => 'col-md-3' ])
                    ->add('examiner', ExaminerType::class)
                ->end()

                ->with('Nivel', ['class' => 'col-md-3' ])
                    ->add('level', LevelType::class, [
                        'required' => false,
                    ])
                ->end()

                ->with('Descripción', ['class' => 'col-md-3'])
                    ->add('numOfPlaces', PositiveIntegerType::class, [
                        'label' => 'Plazas',
                        'attr'=>[
                            'style' => 'width:100px'
                        ]
                    ])
                    ->add('periodicity', PeriodicityType::class)
                    ->add('intensive', IntensiveType::class)
                    ->add('age', AgeType::class)
                ->end()

                ->with('Coste', ['class' => 'col-md-3'])
                    ->add('monthlyPayment', PriceType::class)
                    ->add('enrolmentPayment', PriceType::class)
                    ->add('books')
                ->end()

                ->with('Horario', ['class' => 'col-md-6'])
                    ->add('interval', DateRangePickerType::class, [
                        'field_options'=>[
                            'dp_language'=>'es_ES'
                        ],
                        'field_options_start'=>[
                            'disabled'=> $isStarted
                        ],
                        'field_type' => DatePickerType::class,
                        'block_prefix' => 'course_interval',
                        'disabled' => $isFinished

                    ])
                    ->add('timeSheet', TimeSheetListType::class, [
                        'locked' => $isFinished,
                    ])
                ->end()
            ->end()

            ->with('Alumnos y profesores', ['tab' => true])
                ->with('Profesores', ['class' => 'col-md-5'])
                    ->add('teachers', null, [
                        'by_reference' => false,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('m')
                                ->where('m.teacher = :param')
                                ->setParameter('param', true);
                        },
                    ])
                ->end()
                ->with('Alumnos', ['class' => 'col-md-7'])
                    ->add('students', null, [
                        'by_reference' => false,
                    ])
                ->end()
            ->end()
        ;
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

        return sprintf('%s / semana: %sh / total: %sh / plazas disponibles: %s ', ...[
            $object->getName(),
            $object->getHoursPerWeek(),
            $object->getHoursTotal(),
            $object->getAvailablePlaces()
        ]);

    }


}
