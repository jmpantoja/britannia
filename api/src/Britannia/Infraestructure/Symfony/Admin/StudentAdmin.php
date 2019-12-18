<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\AttendanceRepositoryInterface;
use Britannia\Domain\VO\SchoolCourse;
use Britannia\Infraestructure\Symfony\Form\Type\Student\ContactModeType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\OtherAcademyType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PartOfDayType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PaymentType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\RelativesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\StudentHasCoursesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\TutorType;
use Doctrine\ORM\QueryBuilder;
use IntlDateFormatter;
use PlanB\DDDBundle\Symfony\Form\Type\DateType;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Form\Type\BooleanType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StudentAdmin extends AbstractAdmin
{
    /**
     * @var AttendanceRepositoryInterface
     */
    private $attendanceRepository;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                AttendanceRepositoryInterface $attendanceRepository,
                                EventDispatcherInterface $eventDispatcher

    )
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->attendanceRepository = $attendanceRepository;

        $this->datagridValues = [
            'active' => array('value' => true),
        ];

    }

    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->orderBy('p.active', 'DESC')
            ->addOrderBy('p.fullName.lastName', 'ASC');

        return new ProxyQuery($queryBuilder);
    }


    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);
        return $actions;
    }

    public function getExportFields()
    {
        $fields = parent::getExportFields();

        unset($fields['payment.account']);
        return $fields;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit', 'create', 'delete', 'export']);
        return $collection;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('active', null, [
                'show_filter' => true
            ])
            ->add('fullName', 'doctrine_orm_callback', [
                'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                    if (!$value['value']) {
                        return;
                    }

                    $where = sprintf('%s.fullName.firstName like :name OR %s.fullName.lastName like :name', $alias, $alias);
                    $queryBuilder
                        ->andwhere($where)
                        ->setParameter('name', sprintf('%%%s%%', $value['value']));
                    return true;
                }
            ])
            ->add('Cumple', 'doctrine_orm_callback', [
                'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                    if (!$value['value']) {
                        return;
                    }


                    $where = sprintf('%s.birthMonth = :month', $alias);
                    $queryBuilder
                        ->andwhere($where)
                        ->setParameter('month', $value['value']);
                    return true;
                }
            ], ChoiceType::class, [
                'choices' => [
                    'Enero' => 1,
                    'Febrero' => 2,
                    'Marzo' => 3,
                    'Abril' => 4,
                    'Mayo' => 5,
                    'Junio' => 6,
                    'Julio' => 7,
                    'Agosto' => 8,
                    'Septiembre' => 9,
                    'Octubre' => 10,
                    'Noviembre' => 11,
                    'Diciembre' => 12,
                ],
                'placeholder' => null
            ]);

    }

    protected function configureListFields(ListMapper $listMapper): void
    {

        $listMapper
            ->addIdentifier('fullName.lastName', 'string', [
                'template' => 'admin/student/student_resume_column.html.twig',
                'label' => 'Alumnos'
            ])
            ->add('birthdate', 'date')
            ->add('type', null, [
                'header_style' => 'width:65px',
                'template' => 'admin/student/type_resume_column.html.twig',
                'row_align' => 'center'
            ])
            ->add('active', null, [
                'header_style' => 'width:30px',
                'row_align' => 'center'
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        /** @var Student $subject */
        $subject = $this->getSubject();
        $isAdult = $subject instanceof Adult;

        $formMapper
            ->with('Personal', ['tab' => true]);

        $formMapper
            ->with('Datos personales', ['class' => 'col-md-4'])
            ->add('fullName', FullNameType::class, [
                'sonata_help' => 'Escriba el nombre y apellidos del alumno',
                'label' => 'Nombre y apellidos',
                'required' => true
            ])
            ->add('birthDate', DatePickerType::class, [
                'label' => 'Fecha de nacimiento',
                'format' => IntlDateFormatter::LONG,
                'required' => false
            ])
            ->ifTrue($isAdult)
            ->add('dni', DNIType::class, [
                'empty_data' => null,
                'required' => false
            ])
            ->ifEnd()
            ->end();

        $formMapper
            ->with('Contacto', ['class' => 'col-md-4'])
            ->add('address', PostalAddressType::class, [
                'sonata_help' => 'Escriba una dirección postal',
                'label' => 'Dirección Postal',
                'required' => false,
            ])
            ->add('emails', EmailListType::class, [
                'required' => false,
                'label' => 'Correos Electrónicos',
            ])
            ->add('phoneNumbers', PhoneNumberListType::class, [
                'required' => false,
                'label' => 'Números de teléfono',
            ])
            ->end();

        $formMapper
            ->with($isAdult ? 'Profesión' : 'Colegio', ['class' => 'col-md-4'])
            ->ifTrue($isAdult)
            ->add('job', JobType::class, [
                'sonata_help' => 'Escriba una ocupación y una situación laboral',
                'required' => false,
                'label' => 'Ocupación'
            ])
            ->ifEnd()
            ->ifFalse($isAdult)
            ->add('school', ModelType::class, [
                'label' => 'Colegio',
                'btn_add' => 'Nuevo colegio',
                'placeholder' => ''
            ])
            ->add('schoolCourse', TextType::class, [
                'label' => 'Próximo curso escolar',
                'required' => false
            ])
            ->ifEnd()
            ->end()
            ->end();


        $formMapper
            ->with('Cursos', ['tab' => true])
            ->with('Cursos en Activo ', ['class' => 'col-md-12'])
            ->add('studentHasCourses', StudentHasCoursesType::class, [
                'student' => $subject,
            ])
            ->end()
            ->end();


        $formMapper
            ->with('Pago', ['tab' => true])
            ->with('Descuento', ['class' => 'col-md-6'])
            ->add('free_enrollment', BooleanType::class, [
                'transform' => true,
                'attr' => [
                    'style' => 'width:100px'
                ]
            ])
            ->add('relatives', RelativesType::class, [
                'btn_add' => false,
                'label' => 'Familiares',
                'studentId' => $subject->id(),
                'required' => false
            ])
            ->end()
            ->with('Forma de pago', ['class' => 'col-md-6'])
            ->add('payment', PaymentType::class, [
                'label' => false,
                'required' => true
            ])
            ->end()
            ->end();


        if (!$isAdult) {
            $formMapper
                ->with('Tutores', ['tab' => true]);
            $formMapper
                ->with('Tutores')
                ->add('firstTutorDescription', TextType::class, [
                    'label' => 'Tipo',
                    'sonata_help' => 'padre/madre/abuelo/hermano/...',
                    'required' => false
                ])
                ->add('firstTutor', TutorType::class, [
                    'label' => 'Tutor A',
                    'sonata_help' => 'seleccione un tutor',
                    'required' => true
                ])
                ->add('secondTutorDescription', TextType::class, [
                    'label' => 'Tipo',
                    'sonata_help' => 'padre/madre/abuelo/hermano/...',
                    'required' => false
                ])
                ->add('secondTutor', TutorType::class, [
                    'label' => 'Tutor B',
                    'sonata_help' => 'seleccione un tutor',
                    'required' => false
                ])
                ->end()
                ->end();
        }

        $formMapper
            ->with('Observaciones', ['tab' => true]);

        $formMapper
            ->with('Observaciones A', ['class' => 'col-md-6'])
            ->add('firstComment', WYSIWYGType::class, [
                'label' => false
            ])
            ->end()
            ->with('Observaciones B', ['class' => 'col-md-6'])
            ->add('secondComment', WYSIWYGType::class, [
                'label' => false
            ])
            ->end()
            ->end();

        $formMapper
            ->with('Información Extra', ['tab' => true]);

        $formMapper
            ->with('Preferencias', ['class' => 'col-md-4'])
            ->add('preferredPartOfDay', PartOfDayType::class, [
                'label' => 'Horario'
            ])
            ->add('preferredContactMode', ContactModeType::class, [
                'label' => 'Contacto'
            ])
            ->end();

        $formMapper
            ->with('Estadísticas', ['class' => 'col-md-4'])
            ->add('otherAcademy', OtherAcademyType::class, [
                'required' => false,
                'label' => 'Ha estudiado antes en...',
                'sonata_admin' => $this
            ])
            ->add('firstContact', TextType::class, [
                'required' => false,
                'label' => '¿Como nos conociste?.'
            ])
            ->end();

        $formMapper
            ->with('Condiciones', ['class' => 'col-md-4'])
            ->add('termsOfUseAcademy', null, [
                'label' => 'Acepta las condiciones de uso de la academia'
            ])
            ->add('termsOfUseStudent', null, [
                'label' => 'Acepta las condiciones de uso de la academia'
            ])
            ->add('termsOfUseImageRigths', null, [
                'label' => 'Consentimiento de Imagen'
            ])
            ->end();

        $formMapper->end();

//        $formMapper->get('courses')->addModelTransformer(new CourseDataTransformer($subject, $this->modelManager));
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('active')
            ->add('id')
            ->add('fullName.firstName')
            ->add('fullName.lastName');
    }
}
