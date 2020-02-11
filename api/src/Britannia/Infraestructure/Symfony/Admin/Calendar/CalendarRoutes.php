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

namespace Britannia\Infraestructure\Symfony\Admin\Calendar;


use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;

final class CalendarRoutes extends AdminRoutes
{

    protected function configure(): void
    {
        $this->clearExcept(['list', 'batch']);
        $this->add('calendar.info', '/info');
        $this->add('calendar.change_status', '/change/status');
    }
}
