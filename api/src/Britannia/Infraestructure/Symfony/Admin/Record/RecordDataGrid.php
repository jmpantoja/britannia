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

namespace Britannia\Infraestructure\Symfony\Admin\Record;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class RecordDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->add('date', 'date', [
            'header_style' => 'width:120px',
            'row_align' => 'left'
        ]);

        $this->add('student', 'string', [
            'template' => 'admin/record/record_resume_column.html.twig',
        ]);

    }
}
