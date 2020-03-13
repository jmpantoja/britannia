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


use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;
use Sonata\AdminBundle\Route\RouteCollection;

final class IssueRoutes extends AdminRoutes
{

    public const ROUTE_LIST = ['list', 'edit', 'create', 'delete', 'export', 'show'];


    protected function clearExcept(array $routeList): AdminRoutes
    {
        return parent::clearExcept($routeList);
    }


    protected function configure(): void
    {

        $this->add('issue.read', $this->path('/read'));
    }
}
