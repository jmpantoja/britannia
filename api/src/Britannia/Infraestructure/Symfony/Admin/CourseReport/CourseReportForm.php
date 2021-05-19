<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\CourseReport;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Infraestructure\Symfony\Form\Report\CourseInfo\CourseInformationType;
use Britannia\Infraestructure\Symfony\Form\Report\CourseMarks\CourseCertificateType;
use Britannia\Infraestructure\Symfony\Form\Report\CourseMarks\CourseMarksType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class CourseReportForm extends AdminForm
{

    public function configure(Course $course)
    {
        $this->tabInfo($course);
        $this->tabMarks($course);
        $this->tabCertificate($course);
    }

    private function tabInfo(Course $course)
    {
        $this->tab('Información');
        $this->group('Informacion del curso');
        $this->add('info', CourseInformationType::class, [
            'required' => false,
            'label' => false,
            'mapped' => false,
            'data' => $course,
            'admin' => $this->admin()
        ]);

        $this->add('tab', HiddenType::class, [
            'mapped' => false,
            'data' => '1'
        ]);
    }

    private function tabMarks(Course $course)
    {
        $this->tab('Boletines');
        $this->group('Boletines de notas');
        $this->add('marks', CourseMarksType::class, [
            'required' => false,
            'label' => false,
            'mapped' => false,
            'data' => $course,
            'url' => $this->admin()->generateObjectUrl('range', $course)
        ]);
    }

    private function tabCertificate(Course $course)
    {
        $this->tab('Diplomas');
        $this->group('Diploma fin de curso');
        $this->add('certificate', CourseCertificateType::class, [
            'required' => false,
            'label' => false,
            'mapped' => false,
            'data' => $course
        ]);
    }


}
