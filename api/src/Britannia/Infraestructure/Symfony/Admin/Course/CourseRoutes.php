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


use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;

final class CourseRoutes extends AdminRoutes
{

    protected function configure(): void
    {
        $this->add('report.info', $this->path('/reports/info'));
        $this->add('report.certificate', $this->path('/reports/certificate'));
        $this->add('report.mark', $this->path('/reports/mark'));
    }
}
