<?php

namespace Britannia\Domain\Entity\Student;

use Britannia\Domain\Entity\School\School;
use Britannia\Domain\VO\SchoolCourse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Parent_;

class Child extends Student
{
    /**
     * @var null|School
     */
    private $school;

    /**
     * @var null|SchoolCourse
     */
    private $schoolCourse;

    /**
     * @var string
     */
    private $firstTutorDescription;

    /**
     * @var Tutor
     */
    private $firstTutor;

    /**
     * @var string
     */
    private $secondTutorDescription;

    /**
     * @var Tutor
     */
    private $secondTutor;



    public function __construct()
    {
        parent::__construct();
        $this->tutors = new ArrayCollection();
    }

    /**
     * @return School|null
     */
    public function getSchool(): ?School
    {
        return $this->school;
    }

    /**
     * @param School|null $school
     * @return Child
     */
    public function setSchool(?School $school): self
    {
        $this->school = $school;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSchoolCourse(): ?string
    {
        return $this->schoolCourse;
    }

    /**
     * @param string $schoolCourse
     * @return Child
     */
    public function setSchoolCourse(string $schoolCourse): self
    {
        $this->schoolCourse = $schoolCourse;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstTutorDescription(): ?string
    {
        return $this->firstTutorDescription;
    }

    /**
     * @param string $firstTutorDescription
     * @return Child
     */
    public function setFirstTutorDescription(?string $firstTutorDescription): self
    {
        $this->firstTutorDescription = $firstTutorDescription;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getFirstTutor()
    {
        return $this->firstTutor;
    }

    /**
     * @param mixed $firstTutor
     * @return Child
     */
    public function setFirstTutor($firstTutor): self
    {
        $this->firstTutor = $firstTutor;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondTutorDescription(): ?string
    {
        return $this->secondTutorDescription;
    }

    /**
     * @param string $secondTutorDescription
     * @return Child
     */
    public function setSecondTutorDescription(?string $secondTutorDescription): self
    {
        $this->secondTutorDescription = $secondTutorDescription;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getSecondTutor()
    {
        return $this->secondTutor;
    }

    /**
     * @param mixed $secondTutor
     * @return Child
     */
    public function setSecondTutor($secondTutor): self
    {
        $this->secondTutor = $secondTutor;
        return $this;
    }


}
