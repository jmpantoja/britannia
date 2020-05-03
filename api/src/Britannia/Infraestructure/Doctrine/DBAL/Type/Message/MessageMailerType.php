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


use Britannia\Domain\VO\Message\MessageMailer;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PlanB\DDDBundle\Doctrine\DBAL\Types\EnumType;

final class MessageMailerType extends EnumType
{
    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'MessageMailer';
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return MessageMailer
     */
    function byName(string $value, AbstractPlatform $platform): MessageMailer
    {
        return MessageMailer::byName($value);
    }
}
