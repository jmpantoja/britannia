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
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\Level\Level;
use Doctrine\Common\Collections\ArrayCollection;

class Adult extends Course implements CourseAssessmentInterface,  CourseCalendarInterface, MonthlyPaymentInterface
{
    use AssessmentTrait;
    use CalendarTrait;
    use MonthlyPaymentTrait;

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

        $this->updateAssessment($dto);
        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);
        $this->updatePayment($dto);

        parent::update($dto);
        return $this;
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
