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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Message\DeliveryInterface;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;

class Shipment implements Comparable
{
    use ComparableTrait;

    private ?ShipmentId $id;

    private Message $message;

    private Student $student;

    private int $numOfTries = 0;

    private ?CarbonImmutable $sentAt;

    private ?bool $successful = false;

    private ?string $recipient = null;


    public static function make(Message $message, Student $student): self
    {
        return new self($message, $student);
    }

    private function __construct(Message $message, Student $student)
    {
        $this->id = new ShipmentId();
        $this->message = $message;
        $this->student = $student;
    }

    /**
     * @return ShipmentId
     */
    public function id(): ?ShipmentId
    {
        return $this->id;
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

    /**
     * @return null|string
     */
    public function recipient(): ?string
    {
        return $this->recipient;
    }


    /**
     * @return int
     */
    public function numOfTries(): int
    {
        return $this->numOfTries;
    }

    /**
     * @return CarbonImmutable
     */
    public function sentAt(): ?CarbonImmutable
    {
        return $this->sentAt;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful ?? false;
    }

    public function isActive(): bool
    {
        return $this->student()->isActive();
    }

    public function hash(): string
    {
        return sprintf('%s-%s', ...[
            $this->message()->id(),
            $this->student()->id()
        ]);
    }

    public function send(DeliveryInterface $delivery): bool
    {
        if ($this->isSuccessful()) {
            return true;
        }


        $message = $this->message()->message();
        $subject = $this->message()->subject();

        $this->successful = $delivery->send($this->student(), $message, $subject);
        $this->sentAt = $delivery->date() ?? CarbonImmutable::now();
        $this->recipient = $delivery->recipient($this->student());

        $this->numOfTries++;


        return $this->successful;
    }

}
