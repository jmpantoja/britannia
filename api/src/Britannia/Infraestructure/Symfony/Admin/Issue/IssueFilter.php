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


use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Admin\Issue\Filter\IssueFilterType;
use Britannia\Infraestructure\Symfony\Admin\Issue\Filter\IssueFormType;
use Britannia\Infraestructure\Symfony\Admin\Issue\Filter\IssueStatusFilter;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateType;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Form\Type\DateRangePickerType;

final class IssueFilter extends AdminFilter
{
    public function configure(StaffMember $user)
    {
        $this->add('subject', null, [
            'label' => 'Asunto'
        ]);

        $this->add('message', null, [
            'label' => 'Mensaje',
        ]);

        $this->add('student', null, [
            'label' => 'Alumno',
        ]);

        $this->add('status', IssueStatusFilter::class, [
            'user' => $user,
            'label' => false
        ], IssueFormType::class);

        $this->add('createdAt', 'doctrine_orm_date_range', [
            'label' => false,
            'field_type' => DateRangePickerType::class,
            'field_options' => [
                'field_type' => DateType::class,
                'field_options' => [
                ],
                'field_options_start' => [
                    'label' => 'Desde',
                    'required' => false,
                    'invalid_message' => null,
                ],
                'field_options_end' => [
                    'label' => 'Hasta',
                    'required' => false,
                    'invalid_message' => null,
                ]
            ],
            'advanced_filter' => false,
            'show_filter' => true
        ]);

    }

    private function method()
    {
        return function (ProxyQuery $queryBuilder, $alias, $field, $value) {

        };
    }


}
