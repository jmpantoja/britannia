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

namespace Britannia\Domain\Entity\Message\Template;


use Britannia\Domain\Entity\Message\Template;
use Britannia\Domain\Entity\Message\TemplateDto;

final class SmsTemplate extends Template
{
    public static function make(TemplateDto $dto): self
    {
        return new static($dto);
    }
}
