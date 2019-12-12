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

    public function __construct()
    {
        $this->id = new AcademyId();
    }

    /**
     * @return AcademyId
     */
    public function getId(): AcademyId
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
     * @return Academy
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
