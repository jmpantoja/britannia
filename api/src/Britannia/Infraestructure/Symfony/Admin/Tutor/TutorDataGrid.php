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

namespace Britannia\Infraestructure\Symfony\Admin\Tutor;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class TutorDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->addIdentifier('fullName.lastName', 'string', [
            'template' => 'admin/student/tutor_name_column.html.twig',
            'label' => 'Tutor'
        ]);

        $this->addIdentifier('_collapsed', 'string', [
            'template' => 'admin/student/tutor_collapsed_column.html.twig',
            'label' => 'Tutor'
        ]);
    }
}
