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

namespace Britannia\Infraestructure\Symfony\Admin\Attendance;


use Britannia\Domain\Entity\Staff\StaffMember;
use Carbon\CarbonImmutable;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\Form\Type\DatePickerType;

final class AttendanceFilters extends AdminFilter
{

    /**
     * @var StaffMember
     */
    private StaffMember $user;

    public function setUser(StaffMember $user)
    {
        $this->user = $user;
        return $this;
    }

    public function configure()
    {
        $this->add('day', CallbackFilter::class,
            [
                'label' => 'Fecha',
                'callback' => $this->configureQuery(),
                'show_filter' => true,
                'field_type' => DatePickerType::class
            ]
        );
    }

    /**
     * @return \Closure
     */
    protected function configureQuery(): \Closure
    {
        return function (ProxyQuery $query, $alias, $field, $value) {
            $date = CarbonImmutable::make($value['value']);

            AttendanceQuery::make($query)
                ->setUser($this->user)
                ->setDate($date)
                ->build();

            return true;
        };
    }
}
