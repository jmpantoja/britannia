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

namespace Britannia\Infraestructure\Symfony\Admin\Staff;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class StaffDatagrid extends AdminDataGrid
{
    public function configure()
    {

        $this->add('status', null, [
            'label' => false,
            'header_style' => 'width:76px',
            'template' => 'admin/staff/staff_status_column.html.twig',
            'row_align' => 'left'
        ]);

        $this->add('fullName.lastName', 'string', [
            'template' => 'admin/staff/staff_name_column.html.twig',
            'label' => 'Nombre',
            'header_style' => 'width:200px',
        ]);

        $this->add('courses', 'string', [
            'template' => 'admin/staff/staff_courses_column.html.twig',
            'label' => 'cursos',
            'row_align' => 'left',
            'admin_code' => 'admin.course'
        ]);
        
        $this->add('_collapsed', null, [
            'template' => 'admin/staff/staff_collapsed_column.html.twig',
        ]);
    }
}
