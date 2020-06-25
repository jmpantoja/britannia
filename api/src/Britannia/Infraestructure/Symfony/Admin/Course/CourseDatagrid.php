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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use PHPUnit\Util\Exception;
use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class CourseDatagrid extends AdminDataGrid
{
    public function configure(): self
    {
        $this->add('status', null, [
            'label' => false,
            'header_style' => 'width:76px',
            'template' => 'admin/course/course_status_column.html.twig',
            'row_align' => 'center'
        ]);

        $this->addIdentifier('name', 'string', [
            'template' => 'admin/course/course_name_column.html.twig',
            'label' => 'Nombre',
            'header_style' => 'width:200px'
        ]);

        $this->add('mainTeacher', null, [
            'template' => 'admin/course/course_teacher_column.html.twig',
            'label' => 'Teacher'
        ]);

        $this->add('schedule', null, [
            'label' => 'Horario',
            'header_style' => 'width:230px',
            'template' => 'admin/course/course_schedule_column.html.twig',
            'row_align' => 'left'
        ]);

        $this->add('numOfStudents', null, [
            'label' => 'Plazas',
            'header_style' => 'width:100px',
            'template' => 'admin/course/course_places_column.html.twig',
            'row_align' => 'center'
        ]);

        $this->add('timeRange.start', null, [
            'label' => 'Curso',
            'header_style' => 'width:250px',
            'template' => 'admin/course/course_date_column.html.twig',
            'row_align' => 'left'
        ]);

        $this->add('_action', null, [
            'label' => false,
            'header_style' => 'width:160px;',
            'row_align' => 'right',
            'actions' => [
                'report-info' => [
                    'template' => 'admin/course/course_info_report_action.html.twig'
                ]
            ]
        ]);

        return $this;
    }


}
