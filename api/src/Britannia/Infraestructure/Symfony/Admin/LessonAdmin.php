<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Infraestructure\Symfony\Form\AttendanceListType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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

        $today = new \DateTime();
        $this->datagridValues = [
            'day' => ['value' => $today->format('d M, Y')]
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
            ->add('day',
                CallbackFilter::class,
                [
                    'callback' => function (ProxyQuery $query, $alias, $field, $value) {

                        if (!$value['value']) {
                            $value['value'] = null;
                        }
                        $this->configureQuery($query, $value['value']);

                        return true;
                    },
                    'show_filter' => true,
                    'label' => 'Fecha'
                ],
                DatePickerType::class
            );
    }

    /**
     * @param ProxyQuery $query
     * @param $value
     * @param $user
     */
    private function configureQuery(ProxyQuery $query, \DateTime $day = null): void
    {
        $day = $day ?? new \DateTime();
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
                'label' => false
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
        $course = (string)$object->getCourse();

        return sprintf('%s - %s', $date, $course);
    }


}
