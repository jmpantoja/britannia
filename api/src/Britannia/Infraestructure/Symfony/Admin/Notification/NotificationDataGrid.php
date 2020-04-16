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

namespace Britannia\Infraestructure\Symfony\Admin\Notification;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class NotificationDataGrid extends AdminDataGrid
{

    public function configure()
    {

        $this->add('author', 'string', [
            'header_style' => 'width:200px',
            'row_align' => 'left',
            'label' => 'Autor',
            'template' => 'admin/notification/notification_author_column.html.twig',
        ]);

        $this->add('subject', 'string', [
            'label' => 'Asunto',
            'template' => 'admin/notification/notification_subject_column.html.twig',
        ]);

        $this->add('createdAt', 'date', [
            'header_style' => 'width:120px',
            'row_align' => 'left',
            'label' => 'Fecha'
        ]);

        $this->add('_collapsed', null, [
            'template' => 'admin/notification/notification_collapsed_column.html.twig',
        ]);

    }
}
