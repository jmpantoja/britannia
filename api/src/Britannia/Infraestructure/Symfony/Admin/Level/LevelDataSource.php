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


use Britannia\Domain\Entity\Level\Level;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class LevelDataSource extends AdminDataSource
{
    public function __invoke(Level $level)
    {
        $data['Nivel'] = $this->parse($level->name());

        return $data;
    }
}
