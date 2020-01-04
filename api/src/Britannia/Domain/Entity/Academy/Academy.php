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

namespace Britannia\Domain\Entity\Academy;


use Carbon\CarbonImmutable;

class Academy
{
    /**
     * @var AcademyId
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

    public static function make(AcademyDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(AcademyDto $dto)
    {
        $this->id = new AcademyId();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }

    public function update(AcademyDto $dto)
    {
        $this->name = $dto->name;
        $this->updatedAt = $this->createdAt = CarbonImmutable::now();
    }

    /**
     * @return AcademyId
     */
    public function id(): ?AcademyId
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
