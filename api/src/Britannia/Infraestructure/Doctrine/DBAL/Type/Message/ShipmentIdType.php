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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Message;


use PlanB\DDDBundle\Doctrine\DBAL\Type\EntityIdType;

final class ShipmentIdType extends EntityIdType
{
    public function getName()
    {
        return 'ShipmentId';
    }

    protected function getNamespace()
    {
        return 'Britannia\Domain\Entity\Message';
    }
}
