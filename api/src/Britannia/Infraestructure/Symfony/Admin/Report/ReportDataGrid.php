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

namespace Britannia\Infraestructure\Symfony\Admin\Report;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class ReportDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->add('status', null, [
            'label' => false,
            'header_style' => 'width:76px',
            'template' => 'admin/course/course_status_column.html.twig',
            'row_align' => 'center'
        ]);

        $this->addIdentifier('name', 'string', [
            'template' => 'admin/course/course_name_column.html.twig',
            'label' => 'Cursos'
        ]);
    }
}
