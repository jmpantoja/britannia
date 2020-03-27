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

namespace Britannia\Infraestructure\Symfony\Admin\Message;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class MessageDataGrid extends AdminDataGrid
{
    public function configure()
    {

        $this->add('type', null, [
            'label' => false,
            'template' => 'admin/message/message_type_column.html.twig',
            'header_style' => 'width:75px',
            'row_align' => 'center'

        ]);

        $this->addIdentifier('subject', null, [
            'label' => 'Asunto',
            'header_style' => 'width:250px',
        ]);

        $this->add('message', null, [
            'label' => 'Extracto',
            'template' => 'admin/message/message_message_column.html.twig',
        ]);


        $this->add('completed', null, [
            'label' => false,
            'template' => 'admin/message/message_status_column.html.twig',
            'header_style' => 'width:150px'
        ]);

        $this->add('schedule', null, [
            'label' => 'Fecha de envio',
            'template' => 'admin/message/message_date_column.html.twig',
            'header_style' => 'width:150px'
        ]);

        $this->add('_collapsed', null, [
            'template' => 'admin/message/message_collapsed_column.html.twig',
        ]);
    }
}
