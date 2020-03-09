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

namespace Britannia\Domain\Entity\Notification;


use Carbon\CarbonImmutable;

class Notification
{

    private $id;

    private $subject;
    private $message;
    private $type;
    private $author;
    private $student;
    private $course;

    private $created_at;

    public static function make(NotificationDto $dto): self
    {
        return new self($dto);
    }


    private function __construct(NotificationDto $dto)
    {
        $this->id = new NotificationId();
        $this->update($dto);
        $this->created_at = CarbonImmutable::now();
    }

    public function update(NotificationDto $dto): self
    {
        $this->subject = $dto->subject;
        $this->message = $dto->message;
        $this->type = $dto->type;
        $this->author = $dto->author;
        $this->student = $dto->student;
        $this->course = $dto->course;

        return $this;
    }

    /**
     * @return NotificationId
     */
    public function id(): NotificationId
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function author()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function student()
    {
        return $this->student;
    }

    /**
     * @return mixed
     */
    public function course()
    {
        return $this->course;
    }

    /**
     * @return CarbonImmutable
     */
    public function createdAt(): CarbonImmutable
    {
        return $this->created_at;
    }



}
