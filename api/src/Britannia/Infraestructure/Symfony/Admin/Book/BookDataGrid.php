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

namespace Britannia\Infraestructure\Symfony\Admin\Book;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class BookDataGrid extends AdminDataGrid
{

    public function configure()
    {
        $this->addIdentifier('name', null, [
            'label' => 'Libro',
            'template' => 'admin/core/resume_column.html.twig',
        ]);
    }
}
