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

namespace Britannia\Infraestructure\Symfony\Admin\Report;


use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;

final class ReportRoutes extends AdminRoutes
{

    protected function configure(): void
    {
        $this->clearExcept(['edit']);

        $this->add('pdf', $this->path('/pdf/generate'));
        $this->add('range', $this->path('/ajax/range'));
        $this->add('boundaries-prices', $this->path('/ajax/prices'));
    }
}
