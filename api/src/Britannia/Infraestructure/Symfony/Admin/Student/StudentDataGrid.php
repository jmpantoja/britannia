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

namespace Britannia\Infraestructure\Symfony\Admin\Student;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class StudentDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->add('active', 'string', [
            'label' => false,
            'header_style' => 'width:40px',
            'template' => 'admin/student/student_status_column.html.twig',
            'row_align' => 'center'
        ]);

        $this->add('alert', 'string', [
            'label' => false,
            'header_style' => 'width:80px',
            'template' => 'admin/student/student_alert_column.html.twig',
            'row_align' => 'left'
        ]);

        $this->addIdentifier('fullName.lastName', 'string', [
            'template' => 'admin/student/student_name_column.html.twig',
            'label' => 'Nombre',
            'header_style' => 'width:220px',
        ]);

        $this->add('activeCourses', 'string', [
            'template' => 'admin/student/student_courses_column.html.twig',
            'label' => 'Cursos',
            'row_align' => 'left',
        ]);

        $this->add('birthDate', null, [
            'header_style' => 'width:150px; line-height: 100%',
            'template' => 'admin/student/student_age_column.html.twig',
            'label' => 'Fecha Nac.',
            'row_align' => 'center'
        ]);


        $this->add('createdAt', 'date', [
            'label' => 'Fec. Alta',
            'header_style' => 'width:140px',
        ]);

        $this->add('_collapsed', null, [
            'template' => 'admin/student/student_collapsed_column.html.twig',
        ]);
    }
}
