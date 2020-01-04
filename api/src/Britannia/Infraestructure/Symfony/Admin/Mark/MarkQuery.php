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

namespace Britannia\Infraestructure\Symfony\Admin\Mark;


use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;

final class MarkQuery extends AdminQuery
{

    protected function configure(QueryBuilder $builder, string $alias = 'o'): void
    {
        $builder->where('o.numOfUnits > 0')
            ->orderBy('o.timeTable.end', 'DESC');
    }
}
