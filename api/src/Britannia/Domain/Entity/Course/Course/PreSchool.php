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
use Britannia\Domain\Entity\Course\CourseCalendarInterface;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Course\CoursePaymentInterface;
use Britannia\Domain\Entity\Course\Traits\CalendarTrait;
use Britannia\Domain\Entity\Course\Traits\PaymentTrait;
use Doctrine\Common\Collections\ArrayCollection;

class PreSchool extends Course implements CourseCalendarInterface, CoursePaymentInterface
{
    use CalendarTrait;
    use PaymentTrait;


    public function update(CourseDto $dto): PreSchool
    {
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
}
