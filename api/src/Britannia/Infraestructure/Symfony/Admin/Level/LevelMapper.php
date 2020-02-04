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

namespace Britannia\Infraestructure\Symfony\Admin\Level;


use Britannia\Domain\Entity\Course\Level;
use Britannia\Domain\Entity\Course\LevelDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class LevelMapper extends AdminMapper
{

    protected function className(): string
    {
        return Level::class;
    }

    protected function create(array $values): Level
    {
        $dto = LevelDto::fromArray($values);
        return Level::make($dto);
    }

    /**
     * @param Level $level
     * @param array $values
     * @return Level
     */
    protected function update($level, array $values): Level
    {
        $this->assertType($level);
        $dto = LevelDto::fromArray($values);
        return $level->update($dto);
    }
}
