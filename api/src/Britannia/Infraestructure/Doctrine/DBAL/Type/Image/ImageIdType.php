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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Image;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PlanB\DDDBundle\Doctrine\DBAL\Type\EntityIdType;

class ImageIdType extends EntityIdType
{
    public function getName()
    {
        return 'ImageId';
    }

    protected function getNamespace()
    {
        return 'Britannia\Domain\Entity\Image';
    }
}
