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
            'header_style' => 'width:76px',
            'template' => 'admin/student/status_resume_column.html.twig',
            'row_align' => 'center'
        ]);

        $this->addIdentifier('fullName.lastName', 'string', [
            'template' => 'admin/student/student_resume_column.html.twig',
            'label' => 'Nombre',
            'header_style' => 'width:300px',
        ]);

        $this->add('activeCourses', 'string', [
            'template' => 'admin/student/student_courses.html.twig',
            'label' => 'Cursos',
            'row_align' => 'left',
        ]);

        $this->add('birthdate', 'date', [
            'label' => 'Fecha Nac.',
            'header_style' => 'width:140px',
        ]);

        $this->add('edad', null, [
            'header_style' => 'width:76px; line-height: 100%',
            'template' => 'admin/student/age_resume_column.html.twig',
            'row_align' => 'center'
        ]);
    }
}
