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

namespace Britannia\Infraestructure\Symfony\Admin\MessageTemplate;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class TemplateDataGrid extends AdminDataGrid
{
    public function configure()
    {
        $this->add('type', null, [
            'label' => false,
            'template' => 'admin/template/template_type_column.html.twig',
            'header_style' => 'width:75px',
            'row_align' => 'center'

        ]);

        $this->addIdentifier('name', null, [
            'label' => 'Plantilla',
        ]);

        $this->add('purpose', null, [
            'label' => 'Función',
            'template' => 'admin/template/template_purpose_column.html.twig',
            'header_style' => 'width:150px',
        ]);
    }
}
