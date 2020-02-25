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

namespace Britannia\Infraestructure\Symfony\Admin\SchoolCourse;


use PlanB\DDDBundle\Sonata\Admin\AdminFilter;

final class SchoolCourserFilter extends AdminFilter
{
    public function configure()
    {
        $this->add('course', null, [
            'label' => 'Curso Escolar',
        ]);
    }
}
