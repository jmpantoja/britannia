<?php

namespace Britannia\Domain\Entity\Student;

use Britannia\Domain\Entity\Employment\Situation;
use Britannia\Domain\VO\Employment;
use Britannia\Domain\VO\Student\Job\Job;
use PlanB\DDD\Domain\VO\DNI;

final class Adult extends Student
{
    /** @var DNI */
    private $dni;

    /**
     * @var Job
     */
    private $job;

    public function update(StudentDto $dto): Adult
    {
        parent::update($dto);
        $this->dni = $dto->dni;
        $this->job = $dto->job;

        return $this;
    }

    /**
     * @return DNI
     */
    public function dni(): ?DNI
    {
        return $this->dni;
    }

    /**
     * @return Job
     */
    public function job(): ?Job
    {
        return $this->job;
    }

}
