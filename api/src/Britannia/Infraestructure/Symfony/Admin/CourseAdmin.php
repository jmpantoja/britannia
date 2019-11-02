<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\AgeType;
use Britannia\Infraestructure\Symfony\Form\ExaminerType;
use Britannia\Infraestructure\Symfony\Form\HoursPerWeekType;
use Britannia\Infraestructure\Symfony\Form\IntensiveType;
use Britannia\Infraestructure\Symfony\Form\LessonDefinitionListType;
use Britannia\Infraestructure\Symfony\Form\PeriodicityType;
use Doctrine\ORM\EntityRepository;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;

final class CourseAdmin extends AbstractAdmin
{

    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->orderBy('p.active', 'DESC')
            ->orderBy('p.startDate', 'DESC')
        ;

        return new ProxyQuery($queryBuilder);
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
            ]);;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
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
                    ->add('level', ModelType::class, [
                        'btn_add' => false,
                        'required' => false,
                        'attr'=>[
                            'style' => 'width:200px'
                        ]
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
                    ->add('hoursPerWeek', HoursPerWeekType::class)
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
                        'field_type' => DatePickerType::class,
                        'block_prefix' => 'course_interval'
                    ])
                    ->add('lessons', LessonDefinitionListType::class)
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
}
