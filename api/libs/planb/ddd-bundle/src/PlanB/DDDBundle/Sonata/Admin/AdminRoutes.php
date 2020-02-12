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


use Sonata\AdminBundle\Route\RouteCollection;

abstract class AdminRoutes
{
    public const ROUTE_LIST = ['list', 'edit', 'create', 'delete', 'export'];
    /**
     * @var RouteCollection
     */
    private RouteCollection $collection;
    /**
     * @var string
     */
    private string $idParameter;

    public static function make(RouteCollection $collection, string $idParameter): self
    {
        return new static($collection, $idParameter);
    }

    protected function __construct(RouteCollection $collection, string $idParameter)
    {
        $this->collection = $collection;
        $this->idParameter = $idParameter;

        $this->clearExcept(self::ROUTE_LIST);
    }

    final public function add(
        $name,
        $pattern = null,
        array $defaults = [],
        array $requirements = [],
        array $options = [],
        $host = '',
        array $schemes = [],
        array $methods = [],
        $condition = ''
    ): self
    {
        $this->collection->add($name, $pattern, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
        return $this;
    }

    protected function clearExcept(array $routeList): self
    {
        $this->collection->clearExcept($routeList);
        return $this;
    }

    /**
     * @return string
     */
    protected function path(string $path): string
    {
        return sprintf('%s/%s', ...[
            $this->idParameter,
            ltrim($path, '/')
        ]);
    }

    public function build(): RouteCollection
    {
        $this->configure();
        return $this->collection;
    }

    abstract protected function configure(): void;
}
