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

namespace Britannia\Domain\Entity\Course\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\Entity\Course\CourseCalendarInterface;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Course\MonthlyPaymentInterface;
use Britannia\Domain\Entity\Course\Traits\AssessmentTrait;
use Britannia\Domain\Entity\Course\Traits\CalendarTrait;
use Britannia\Domain\Entity\Course\Traits\MonthlyPaymentTrait;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class School extends Course implements CourseAssessmentInterface, CourseCalendarInterface, MonthlyPaymentInterface
{
    use AssessmentTrait;
    use CalendarTrait;
    use MonthlyPaymentTrait;

    /**
     * @var null|string
     */
    private $schoolCourses;

    public function __construct(SchoolDto $dto)
    {
        $this->assessment = $dto->assessment;
        $this->terms = new ArrayCollection();
        $this->schoolCourses = new ArrayCollection();
        $this->lessons = new ArrayCollection();

        parent::__construct($dto);
    }

    public function update(CourseDto $dto): School
    {
        $this->setSchoolCourses($dto->schoolCourses);
        $this->updateAssessment($dto);

        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);
        $this->updatePayment($dto);

        parent::update($dto);
        return $this;
    }

    /**
     * @return string|null
     */
    public function schoolCourses(): array
    {
        return $this->schoolCourses;
    }

    /**
     * @param array $schoolCourses
     * @return School
     */
    private function setSchoolCourses(array $schoolCourses): School
    {
        $this->schoolCourses = $schoolCourses;
        return $this;
    }


    public function assessment(): Assessment
    {
        return $this->assessment ?? Assessment::defaultForShool();
    }

}
