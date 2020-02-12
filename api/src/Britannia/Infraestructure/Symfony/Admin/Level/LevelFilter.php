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


use PlanB\DDDBundle\Sonata\Admin\AdminFilter;

final class LevelFilter extends AdminFilter
{

    public function configure()
    {
        $this->add('name', null, [
            'label' => 'Nivel',
        ]);
    }
}
