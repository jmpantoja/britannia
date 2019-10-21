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


use PlanB\DDD\Domain\Filter\ProperName;

class School
{
    private $id;

    /**
     * @var string
     */
    private $name;

    public function __construct()
    {
        $this->id = new SchoolId();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return School
     */
    public function setName(string $name): self
    {
        $filter = new ProperName();

        $this->name = $filter->filter($name);
        return $this;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }

}
