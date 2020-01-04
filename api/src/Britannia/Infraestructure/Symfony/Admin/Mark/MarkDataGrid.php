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

namespace Britannia\Infraestructure\Symfony\Admin\Mark;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class MarkDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->addIdentifier('name', 'string', [
            'template' => 'admin/course/course_list_field.html.twig',
            'label' => 'Cursos'
        ]);

        $this->add('status', null, [
            'header_style' => 'width:30px;',
            'template' => 'admin/course/status_list_field.html.twig',
            'row_align' => 'center'
        ]);
    }
}
