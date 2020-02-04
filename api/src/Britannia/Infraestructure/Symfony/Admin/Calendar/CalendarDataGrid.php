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

namespace Britannia\Infraestructure\Symfony\Admin\Calendar;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class CalendarDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->add('workingDay', null, [
                'editable' => true,
                'header_style' => 'width:30px',
                'label' => 'Laborable',
                'row_align' => 'center'
            ]);

        $this->add('date', 'date', [
                'template' => 'admin/calendar/calendar_resume_column.html.twig'
        ]);
    }
}
