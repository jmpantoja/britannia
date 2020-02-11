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

namespace Britannia\Infraestructure\Symfony\Admin\School;


use Britannia\Domain\Entity\School\School;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class SchoolDataSource extends AdminDataSource
{

    public function __invoke(School $school)
    {
        $data['Nombre'] = $this->parse($school->name());
        return $data;
    }
}
