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

namespace Britannia\Domain\Entity\SchoolCourse;


use Britannia\Domain\VO\SchoolCourse\SchoolLevel;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\PositiveInteger;

final class SchoolCourseDto extends Dto
{
    public PositiveInteger $course;
    public SchoolLevel $level;

}
