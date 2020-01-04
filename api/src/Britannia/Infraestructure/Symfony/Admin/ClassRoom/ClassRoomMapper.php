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
use Britannia\Domain\Entity\ClassRoom\ClassRoomDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class ClassRoomMapper extends AdminMapper
{

    protected function className(): string
    {
        return ClassRoom::class;
    }

    protected function create(array $values): ClassRoom
    {
        $dto = ClassRoomDto::fromArray($values);
        return ClassRoom::make($dto);
    }

    /**
     * @param ClassRoom $classRoom
     * @param array $values
     */
    protected function update($classRoom, array $values)
    {
        $this->assertType($classRoom);

        $dto = ClassRoomDto::fromArray($values);
        $classRoom->update($dto);
    }
}
