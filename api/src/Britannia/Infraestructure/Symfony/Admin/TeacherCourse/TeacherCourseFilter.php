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

namespace Britannia\Infraestructure\Symfony\Admin\TeacherCourse;


use Britannia\Domain\VO\Course\CourseStatus;
use Carbon\CarbonImmutable;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TeacherCourseFilter extends AdminFilter
{
    public function configure()
    {
        $this->add('name', null, [
            'advanced_filter' => false,
            'show_filter' => true
        ]);

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

        $this->add('course', 'doctrine_orm_callback', [
            'label' => 'Curso Escolar',
            'field_type' => ChoiceType::class,
            'field_options' => [
                'label' => 'Estado',
                'choices' => $this->calculeCourses(),
                'placeholder' => 'Todos'
            ],

            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                if (!$value['value']) {
                    return;
                }

                $year = $value['value'];
                $start = CarbonImmutable::create($year);
                $end = $start->endOfYear();

                $where = sprintf('%s.timeRange.end > :start AND %s.timeRange.end < :end', $alias, $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('start', $start)
                    ->setParameter('end', $end);

                return true;
            }
        ]);

    }

    private function calculeCourses(): array
    {
        $year = 2016;
        $lastYear = CarbonImmutable::today()->year + 2;

        $courses = [];
        while ($year <= $lastYear) {
            $label = sprintf('%s/%s', $year - 1, $year);
            $courses[$label] = $year;
            $year++;
        }

        return $courses;
    }
}
