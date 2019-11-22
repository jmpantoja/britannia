<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\AttendanceListType;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Security\Core\Security;

final class LessonAdmin extends AbstractAdmin
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(string $code, string $class, string $baseControllerName, Security $security)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->security = $security;
        $today = Carbon::now();
        $this->datagridValues = [
            'day' => ['value' => $today->format('d M. Y')]
        ];
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
        return $collection;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $this->configureQuery($query);

        return $query;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('day', CallbackFilter::class,
                [
                    'label' => 'Fecha',
                    'callback' => function (ProxyQuery $query, $alias, $field, $value) {
                        $date = CarbonImmutable::make($value['value']);

                        $this->configureQuery($query, $date);
                        return true;
                    },
                    'show_filter' => true,
                    'field_type' => DatePickerType::class
                ]
            );
    }

    /**
     * @param ProxyQuery $query
     * @param CarbonImmutable|null $day
     */
    private function configureQuery(ProxyQuery $query, ?CarbonImmutable $day = null): void
    {
        $day = $day ?? Carbon::now();
        $day->setTime(0, 0);

        $user = $this->security->getUser();
        $courses = $user->getActiveCourses();

        $query
            ->getQueryBuilder()
            ->where('o.day= :day')
            ->andWhere('o.course IN (:courses)')
            ->setParameter('day', $day)
            ->setParameter('courses', $courses);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->setListMode('mosaic');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $lesson = $this->getSubject();


        $formMapper
            ->add('attendances', AttendanceListType::class, [
                'lesson' => $lesson,
                'required' => false,
                'label' => $this->toString($lesson)
            ]);
    }

    public function isGranted($name, $object = null)
    {
        $isGranted = parent::isGranted($name, $object);
        if ($name !== 'EDIT') {
            return $isGranted;
        }

        if ($isGranted) {
            $user = $this->security->getUser();
            $isGranted = $user->hasCourse($object->getCourse());
        }

        return $isGranted;
    }

    public function toString($object)
    {
        $date = $object->getDay()->format('d/m/Y');
        $course = $object->getCourse()->getName();

        return sprintf('%s - %s', $date, $course);
    }


}
