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


use Britannia\Domain\VO\Age;
use Britannia\Domain\VO\Examiner;
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


    public function __construct()
    {
        $this->id = new ClassRoomId();
    }

    /**
     * @return ClassRoomId
     */
    public function getId(): ClassRoomId
    {
        return $this->id;
    }


    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return ClassRoom
     */
    public function setName(?string $name): ClassRoom
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|Price
     */
    public function getCapacity(): ?PositiveInteger
    {
        return $this->capacity;
    }

    /**
     * @param null|PositiveInteger $capacity
     * @return ClassRoom
     */
    public function setCapacity(?PositiveInteger $capacity): ClassRoom
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }


}
