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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use Britannia\Domain\VO\Course\CourseStatus;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class CourseFilter extends AdminFilter
{
    public function configure()
    {
        $this->add('name', null, [
            'advanced_filter' => false,
            'show_filter' => true
        ]);

//        $this->add('timeRange.status', 'doctrine_orm_string', [
//            'show_filter' => true,
//            'advanced_filter' => false,
//            'field_type' => ChoiceType::class,
//            'field_options' => [
//                'label' => 'Estado',
//                'choices' => array_flip(CourseStatus::getConstants()),
//                'placeholder' => 'Todos'
//            ],
//        ]);

        $this->add('status', 'doctrine_orm_callback', [
            'label' => 'Estado',
            'field_type' => ChoiceType::class,
            'field_options' => [
                'label' => 'Estado',
                'choices' => array_flip(CourseStatus::getConstants()),
                'placeholder' => 'Todos'
            ],

            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {

                if (!$value['value']) {
                    return;
                }

                $where = sprintf('%s.timeRange.status = :status', $alias, $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('status', $value['value']);

                return true;
            }
        ]);
    }
}
