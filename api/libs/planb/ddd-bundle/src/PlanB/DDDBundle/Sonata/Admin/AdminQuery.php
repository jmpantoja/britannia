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

namespace PlanB\DDDBundle\Sonata\Admin;


use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

abstract class AdminQuery
{
    /**
     * @var ProxyQueryInterface
     */
    private ProxyQueryInterface $query;

    public static function make(ProxyQuery $query): self
    {
        return new static($query);
    }

    protected function __construct(ProxyQuery $query)
    {
        $this->query = $query;
    }

    public function build(): ProxyQuery
    {
        /** @var QueryBuilder $builder */
        $builder = $this->query->getQueryBuilder();
        $aliases = $builder->getRootAliases();
        $alias = array_shift($aliases);

        $this->configure($builder, $alias);

        return $this->query;
    }

    abstract protected function configure(QueryBuilder $builder, string $alias = 'o'): void;


}
