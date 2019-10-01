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


use Britannia\Domain\VO\Job;
use PlanB\DDD\Domain\VO\FullName;

class Borrame
{
    private $id;
    private $fullName;
    private $address;
    private $dni;

    private $job;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Borrame
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     * @return Borrame
     */
    public function setFullName(?FullName $fullName)
    {

        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Borrame
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     * @return Borrame
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     * @return Borrame
     */
    public function setJob(?Job $job)
    {
        $this->job = $job;
        return $this;
    }




}
