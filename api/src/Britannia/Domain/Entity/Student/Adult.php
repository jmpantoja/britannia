<?php

namespace Britannia\Domain\Entity\Student;

use Britannia\Domain\Entity\Employment\Situation;
use Britannia\Domain\VO\Employment;
use PlanB\DDD\Domain\VO\DNI;

class Adult extends Student
{
    /** @var DNI */
    private $dni;

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
    public function setDni(DNI $dni): Adult
    {
        $this->dni = $dni;
        return $this;
    }


}
