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
        $this->addIdentifier('fullName.lastName', 'string', [
            'template' => 'admin/student/student_resume_column.html.twig',
            'label' => 'Alumnos'
        ]);

        $this->add('birthdate', 'date');

        $this->add('type', null, [
            'header_style' => 'width:65px',
            'template' => 'admin/student/type_resume_column.html.twig',
            'row_align' => 'center'
        ]);

        $this->add('active', null, [
            'header_style' => 'width:30px',
            'row_align' => 'center'
        ]);
    }
}
