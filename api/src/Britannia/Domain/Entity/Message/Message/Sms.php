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

namespace Britannia\Domain\Entity\Message\Message;


use Britannia\Domain\Entity\Message\Message;
use Britannia\Domain\Entity\Message\MessageDto;

class Sms extends Message
{
    /** @var ?bool */
    private $successful = null;


    public static function make(SmsDto $dto)
    {
        return new static($dto);
    }


    public function update(MessageDto $dto): Message
    {
        return  parent::update($dto);
    }

    public function successful(): ?bool
    {
        return $this->successful;
    }
}
