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
use Britannia\Domain\Entity\Course\CoursePaymentInterface;
use Britannia\Domain\Entity\Course\Traits\AssessmentTrait;
use Britannia\Domain\Entity\Course\Traits\CalendarTrait;
use Britannia\Domain\Entity\Course\Traits\PaymentTrait;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Doctrine\Common\Collections\ArrayCollection;

final class School extends Course implements CourseAssessmentInterface, CourseCalendarInterface, CoursePaymentInterface
{
    use AssessmentTrait;
    use CalendarTrait;
    use PaymentTrait;

    /**
     * @var null|string
     */
    private $schoolCourse;

    public function __construct(SchoolDto $dto)
    {
        $this->assessment = $dto->assessment;
        $this->terms = new ArrayCollection();

        parent::__construct($dto);
    }


    public function update(CourseDto $dto): School
    {
        $this->schoolCourse = $dto->schoolCourse;
        $this->updateAssessment($dto);
        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);
        $this->updatePayment($dto);

        parent::update($dto);
        return $this;
    }

    /**
     * @return string|null
     */
    public function schoolCourse(): ?string
    {
        return $this->schoolCourse;
    }

    public function assessment(): Assessment
    {
        return  $this->assessment ?? Assessment::defaultForShool();
    }

}
