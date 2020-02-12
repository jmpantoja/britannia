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

namespace Britannia\Infraestructure\Symfony\Admin\ClassRoom;


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class ClassRoomDataSource extends AdminDataSource
{

    public function __invoke(ClassRoom $classRoom)
    {
        $data['Nombre'] = $this->parse($classRoom->name());
        $data['Capacidad'] = $this->parse($classRoom->capacity());

        return $data;
    }
}
