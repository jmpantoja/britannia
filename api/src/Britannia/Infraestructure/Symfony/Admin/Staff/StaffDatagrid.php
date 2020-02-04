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

        $this->addIdentifier('id', 'string', [
            'template' => 'admin/staff/staff_resume_column.html.twig',
            'label' => 'Empleado'
        ]);

//        $this->add('courses', null, [
//            'admin_code' => 'admin.course'
//           // 'header_style' => 'width:150px',
//            //'row_align' => 'left'
//        ]);

        $this->add('userName', null, [
            'header_style' => 'width:150px',
            'row_align' => 'left'
        ]);
    }
}
