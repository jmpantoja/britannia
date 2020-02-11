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

namespace Britannia\Infraestructure\Symfony\Admin\Academy;


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Course\Course;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class AcademyDataSource extends AdminDataSource
{

    public function __invoke(Academy $academy)
    {
        $data['Nombre'] = $this->parse($academy->name());
        return $data;
    }
}
