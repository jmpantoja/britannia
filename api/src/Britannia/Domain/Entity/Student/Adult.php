<?php

namespace Britannia\Domain\Entity\Student;

use Britannia\Domain\Entity\Employment\Situation;
use Britannia\Domain\VO\Employment;
use Britannia\Domain\VO\Job;
use PlanB\DDD\Domain\VO\DNI;

class Adult extends Student
{
    /** @var DNI */
    private $dni;

    /**
     * @var Job
     */
    private $job;

    /**
     * @return DNI
     */
    public function getDni(): ?DNI
    {
        return $this->dni;
    }

    /**
     * @param DNI $dni
     * @return Adult
     */
    public function setDni(?DNI $dni): Adult
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * @return Job
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job $job
     * @return Adult
     */
    public function setJob(?Job $job): Adult
    {
        $this->job = $job;
        return $this;
    }

}
