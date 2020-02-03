<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Report;

use Britannia\Domain\VO\Course\CourseStatus;
use mikehaertl\pdftk\Pdf;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class ReportAdmin extends AbstractAdmin
{

    protected $baseRouteName = 'admin_britannia_domain_course_report';
    protected $baseRoutePattern = '/britannia/domain/course-report';

    /**
     * @var ReportTools
     */
    private ReportTools $adminTools;

    public function __construct($code, $class, $baseControllerName, ReportTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
        $this->dataGridValues();

//
//        $path = realpath('../templates/admin/report/course_certificate.pdf');
//        $target = realpath('../var/') . '/pepe.pdf';
//
//        $pdf = new Pdf($path);
//
//        $pdf->fillForm([
//            'Name' => 'Nombre y Apellidos',
//            'Text2' => 'Curso',
//            'Text3' => 'Duración',
//            'Text4' => 'Mes inicio',
//            'Text5' => 'Mes fin',
//            'Text6' => 'Nota',
//            'Text7' => 'Fecha',
//
//            '47' => '1.5',
//            '68' => '2.5',
//            'Text12' => '3.5',
//            'Text13' => '4.5',
//            'Text14' => '5.5',
//            'Text15' => '6.5',
//
//            'TRTE' => 'grammar range ',
//            'GWEGEW' => 'vocabulary range',
//            'Text8' => 'speaking range',
//            'Text9' => 'listening range',
//            'Text10' => 'reading range',
//            'Text11' => 'writing range',
//        ])
//            ->needAppearances()
//            ->saveAs($target);
//
//
//        die(__METHOD__);
    }

    /**
     * @return ReportTools
     */
    public function adminTools(): ReportTools
    {
        return $this->adminTools;
    }

    protected function dataGridValues(): void
    {
        $status = CourseStatus::ACTIVE();
        $this->datagridValues = [
            'status' => ['value' => $status->getName()]
        ];
    }

    public function getBatchActions()
    {
        return [];
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        return $this->adminTools()
            ->query($query)
            ->build();

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection = $this->adminTools()
            ->routes($collection, $this->getRouterIdParameter())
            ->build();

        return $collection;
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


        $course = $this->getSubject();
        $this->adminTools()
            ->form($formMapper)
            ->configure($course);
    }


    public function update($object)
    {
        return $object;
    }


}
