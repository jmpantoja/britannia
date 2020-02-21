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
use Britannia\Domain\Entity\Level\Level;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Doctrine\Common\Collections\ArrayCollection;

final class Adult extends Course implements CourseAssessmentInterface,  CourseCalendarInterface, CoursePaymentInterface
{

    use AssessmentTrait;
    use CalendarTrait;
    use PaymentTrait;


    /**
     * @var null|Intensive
     */
    private $intensive;

    /**
     * @var null|Examiner
     */
    private $examiner;

    /**
     * @var null|Level
     */
    private $level;


    public function __construct(AdultDto $dto)
    {
        $this->assessment = $dto->assessment;
        $this->terms = new ArrayCollection();

        parent::__construct($dto);
    }


    public function update(CourseDto $dto): self
    {
        $this->examiner = $dto->examiner;
        $this->level = $dto->level;
        $this->intensive = $dto->intensive;

        $this->updateAssessment($dto);
        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);
        $this->updatePayment($dto);

        parent::update($dto);
        return $this;
    }

    /**
     * @return Intensive|null
     */
    public function intensive(): ?Intensive
    {
        return $this->intensive;
    }


    /**
     * @return Examiner|null
     */
    public function examiner(): ?Examiner
    {
        return $this->examiner;
    }

    /**
     * @return Level|null
     */
    public function level(): ?Level
    {
        return $this->level;
    }


    public function assessment(): Assessment
    {
        return  $this->assessment ?? Assessment::defaultForAdults();
    }

}
