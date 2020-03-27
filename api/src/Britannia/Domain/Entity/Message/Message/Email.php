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
use Britannia\Domain\Entity\Message\SenderInterface;
use Britannia\Domain\VO\Message\MessageMailer;
use Britannia\Infraestructure\Symfony\Service\Message\EmailDelivery;

class Email extends Message
{

    /** @var MessageMailer */
    private $mailer;

    public static function make(EmailDto $dto)
    {
        return new static($dto);
    }


    public function update(MessageDto $dto): Message
    {
        parent::update($dto);
        $this->mailer = $dto->mailer;
        return $this;
    }

    /**
     * @return MessageMailer
     */
    public function mailer(): MessageMailer
    {
        return $this->mailer;
    }


}
