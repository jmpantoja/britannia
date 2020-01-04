<?php

namespace Britannia\Domain\Entity\Student;

use Britannia\Domain\Entity\School\School;
use Britannia\Domain\VO\SchoolCourse;
use Doctrine\Common\Collections\ArrayCollection;

final class Child extends Student
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

    /**
     * @var ArrayCollection
     */
    private ArrayCollection $tutors;



    protected function __construct(StudentDto $dto)
    {
        $this->tutors = new ArrayCollection();
        parent::__construct($dto);

    }

    public function update(StudentDto $dto): Child
    {
        $this->school = $dto->school;
        $this->schoolCourse = $dto->schoolCourse;

        $this->firstTutor = $dto->firstTutor;
        $this->firstTutorDescription = $dto->firstTutorDescription;

        $this->secondTutor = $dto->secondTutor;
        $this->secondTutorDescription = $dto->secondTutorDescription;

        return parent::update($dto);
    }


    /**
     * @return School|null
     */
    public function school(): ?School
    {
        return $this->school;
    }

    /**
     * @return string|null
     */
    public function schoolCourse(): ?string
    {
        return $this->schoolCourse;
    }


    /**
     * @return string
     */
    public function firstTutorDescription(): ?string
    {
        return $this->firstTutorDescription;
    }

    /**
     * @return mixed
     */
    public function firstTutor(): ?Tutor
    {
        return $this->firstTutor;
    }

    /**
     * @return string
     */
    public function secondTutorDescription(): ?string
    {
        return $this->secondTutorDescription;
    }

    /**
     * @return mixed
     */
    public function secondTutor(): ?Tutor
    {
        return $this->secondTutor;
    }

}
