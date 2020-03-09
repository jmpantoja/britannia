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

namespace Britannia\Application\UseCase\Record;


use Britannia\Domain\Entity\Notification\NotificationDto;
use Britannia\Domain\Entity\Notification\NotificationEventInterface;

class CreateNotification
{

    /**
     * @var NotificationDto
     */
    private NotificationDto $dto;

    private function __construct(NotificationDto $dto)
    {
        $this->dto = $dto;
    }

    public static function fromEvent(NotificationEventInterface $event): self
    {
        $dto = $event->dto();
        return new self($dto);
    }

    /**
     * @return NotificationDto
     */
    public function dto(): NotificationDto
    {
        return $this->dto;
    }
}
