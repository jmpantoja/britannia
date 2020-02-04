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

namespace Britannia\Domain\Entity\Course;


use Britannia\Domain\Entity\Lesson\UpdateCalendarOrder;
use Britannia\Domain\VO\Course\Age\Age;
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\Periodicity\Periodicity;
use PlanB\DDD\Domain\Model\Dto;

class AdultDto extends CourseDto
{
    public ?Intensive $intensive;

    public ?Examiner $examiner = null;

    public ?Level $level = null;

}
