<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Student;

use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\SchoolCourse;
use Britannia\Infraestructure\Symfony\Form\Type\Student\ContactModeType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\OtherAcademyType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PartOfDayType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PaymentType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\RelativesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\StudentHasCoursesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\TutorType;
use IntlDateFormatter;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
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
use Sonata\Form\Type\BooleanType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StudentAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        'active' => ['value' => true],
    ];
    /**
     * @var StudentTools
     */
    private StudentTools $adminTools;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName,
                                StudentTools $adminTools
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return StudentTools
     */
    public function adminTools(): StudentTools
    {
        return $this->adminTools;
    }

    public function getBatchActions()
    {
        return [];
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(AdminRoutes::ROUTE_LIST);
        return $collection;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        return $this->adminTools()
            ->query($query)
            ->build();
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
        $student = $this->getSubject();

        $this->adminTools()
            ->form($formMapper)
            ->configure($student);
    }


}
