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

namespace Britannia\Infraestructure\Symfony\Admin\CourseReport;


use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;

final class CourseReportQuery extends AdminQuery
{

    protected function configure(QueryBuilder $builder, string $alias = 'o'): void
    {
        $builder->orderBy('o.timeTable.end', 'DESC');
    }
}
