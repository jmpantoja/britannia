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


use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Lesson\UpdateCalendarOrder;

use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\VO\Course\Age\Age;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\Level\Level;
use Britannia\Domain\VO\Course\Periodicity\Periodicity;

class AdultDto extends CourseDto implements AssessmentDtoInterface
{
    public ?Intensive $intensive;

    public ?Examiner $examiner = null;

    public ?Level $level = null;


    public Assessment $assessment;

    public AssessmentGenerator $assessmentGenerator;

    /**
     * @return Assessment
     */
    public function assessment(): Assessment
    {
        return $this->assessment ?? Assessment::defaultForAdults();
    }

    /**
     * @return AssessmentGenerator
     */
    public function assessmentGenerator(): AssessmentGenerator
    {
        return $this->assessmentGenerator;
    }


}
