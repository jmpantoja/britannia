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

namespace Britannia\Domain\Entity\ClassRoom;


use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;

class ClassRoom
{
    /**
     * @var ClassRoomId
     */
    private $id;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|Price
     */
    private $capacity;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    public static function make(ClassRoomDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(ClassRoomDto $dto)
    {
        $this->id = new ClassRoomId();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }


    public function update(ClassRoomDto $dto): self
    {
        $this->name = $dto->name;
        $this->capacity = $dto->capacity;
        $this->updatedAt = CarbonImmutable::now();

        return $this;
    }

    /**
     * @return ClassRoomId
     */
    public function id(): ?ClassRoomId
    {
        return $this->id;
    }

    /**
     * @return Price
     */
    public function capacity(): PositiveInteger
    {
        return $this->capacity;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name ?? (string) $this->id();
    }


    public function __toString()
    {
        return $this->name();
    }
}
