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

namespace Britannia\Infraestructure\Symfony\Admin\Issue;


use PlanB\DDDBundle\Sonata\Admin\AdminDataGrid;

final class IssueDatagrid extends AdminDataGrid
{
    public function configure(): self
    {

        $this->add('author.fullName.firstName', 'string', [
            'header_style' => 'width:200px',
            'row_align' => 'left',
            'label' => 'Autor',
            'template' => 'admin/issue/issue_author_column.html.twig',
        ]);

        $this->add('subject', 'string', [
            'label' => 'Asunto',
            'template' => 'admin/issue/issue_subject_column.html.twig',
        ]);

        $this->add('student.fullName.firstName', 'string', [
            'template' => 'admin/issue/issue_student_column.html.twig',
            'label' => 'Alumno',
            'header_style' => 'width:200px; text-align:left',
            'row_align' => 'right',
        ]);

        $this->add('createdAt', 'date', [
            'header_style' => 'width:120px',
            'row_align' => 'left',
            'label' => 'Fecha'
        ]);


        return $this;
    }


}
