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

namespace Britannia\Domain\Entity\Message;


use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;
use Britannia\Domain\Service\Message\DeliveryInterface;
use Britannia\Domain\Service\Message\MailerInterface;
use Britannia\Domain\Service\Message\SmsLauncherInterface;

final class MessageHasBeenSent extends NotificationEvent
{
    private string $messageType = 'mensaje';

    private string $messageSubject = '';

    public static function make(Shipment $shipment, DeliveryInterface $delivery): self
    {
        return self::builder($shipment->student())
            ->withDelivery($delivery)
            ->withDate($shipment->message()->createdAt())
            ->withMessageSubject($shipment->message()->subject());
    }

    public function type(): TypeOfNotification
    {
        return TypeOfNotification::MESSAGE_SENT();
    }

    protected function makeSubject(): string
    {
        return sprintf('Se ha enviado un %s a %s con el asunto <b>%s</b>', ...[
            $this->messageType,
            $this->student->name(),
            $this->messageSubject
        ]);
    }

    private function withDelivery(DeliveryInterface $delivery): self
    {
        if ($delivery instanceof MailerInterface) {
            $this->messageType = 'email';
            return $this;
        }

        if ($delivery instanceof SmsLauncherInterface) {
            $this->messageType = 'sms';
            return $this;
        }

        $this->messageType = 'mensaje';
        return $this;
    }

    private function withMessageSubject(string $subject): self
    {
        $this->messageSubject = $subject;
        return $this;
    }
}
