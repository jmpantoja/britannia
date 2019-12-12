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


class Level
{
    /**
     * @var LevelId
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    public function __construct()
    {
        $this->id = new LevelId();
    }

    /**
     * @return LevelId
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Level
     */
    public function setName(string $name): Level
    {
        $this->name = $name;
        return $this;
    }


}
