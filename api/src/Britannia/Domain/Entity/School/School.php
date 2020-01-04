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

namespace Britannia\Domain\Entity\School;


use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Filter\ProperName;

class School
{
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

    public static function make(SchoolDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(SchoolDto $dto)
    {
        $this->id = new SchoolId();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }


    public function update(SchoolDto $dto)
    {
        $this->setName($dto->name);
        $this->updatedAt = CarbonImmutable::now();
    }

    /**
     * @return mixed
     */
    public function id(): ?SchoolId
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return School
     */
    private function setName(?string $name): self
    {
        $filter = new ProperName();
        $this->name = $filter->filter($name);
        return $this;
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
