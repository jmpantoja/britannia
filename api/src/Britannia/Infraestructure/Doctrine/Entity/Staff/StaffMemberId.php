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

namespace Britannia\Infraestructure\Doctrine\Entity\Staff;


use Britannia\Infraestructure\Doctrine\Entity\DoctrineEntityId;

class StaffMemberId extends DoctrineEntityId
{
    public function getName()
    {
        return 'StaffMemberId';
    }

    protected function getNamespace()
    {
        return 'Britannia\Domain\Entity\Staff';
    }
}
