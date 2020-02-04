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

namespace Britannia\Domain\Entity\Course;


use Carbon\CarbonImmutable;

final class Level
{
    /**
     * @var LevelId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    public static function make(LevelDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(LevelDto $dto)
    {
        $this->id = new LevelId();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }

    public function update(LevelDto $dto): self
    {
        $this->name = $dto->name;
        $this->updatedAt = CarbonImmutable::now();
        return $this;
    }

    /**
     * @return LevelId
     */
    public function id(): ?LevelId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name ?? (string)$this->id();
    }

    public function __toString()
    {
        return $this->name();
    }

}
