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

namespace Britannia\Domain\Entity;


use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\FullName;

class Borrame
{

    private $id;

    /**
     * @var FullName
     */
    private $fullName;

    /**
     * @var DNI
     */
    private $dni;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FullName
     */
    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName $fullName
     * @return Borrame
     */
    public function setFullName(FullName $fullName): Borrame
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return DNI
     */
    public function getDni(): ?DNI
    {
        return $this->dni;
    }

    /**
     * @param DNI $dni
     * @return Borrame
     */
    public function setDni(DNI $dni): Borrame
    {
        $this->dni = $dni;
        return $this;
    }


}
