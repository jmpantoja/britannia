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


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class CourseDatagrid extends AdminDataGrid
{

    public function configure(): self
    {
        $this->addId()
//            ->addDates()
            ->addStatus()
            ->addActions();

        return $this;
    }

    private function addId(): self
    {
        $this->addIdentifier('name', 'string', [
            'template' => 'admin/course/course_list_field.html.twig',
            'label' => 'Cursos'
        ]);
        return $this;
    }


//    private function addDates(): self
//    {
//        $this->add('start', 'string', [
////            'template' => 'admin/course/course_list_field.html.twig',
////            'label' => 'Cursos'
//        ]);
//        return $this;
//    }


    private function addStatus(): self
    {
        $this->add('status', null, [
            'header_style' => 'width:30px;',
            'template' => 'admin/course/status_list_field.html.twig',
            'row_align' => 'center'
        ]);

        return $this;
    }

    private function addActions(): self
    {
        $this->add('_action', null, [
            'label' => 'Informes',
            'header_style' => 'width:210px; text-align: center',
            'row_align' => 'center',
            'actions' => [
                'report-info' => [
                    'template' => 'admin/course/course_info_report_action.html.twig'
                ]
            ]
        ]);

        return $this;
    }
}
