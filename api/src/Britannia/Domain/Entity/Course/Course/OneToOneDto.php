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
use Britannia\Domain\Entity\Course\Pass\PassList;
use Britannia\Domain\VO\Course\Pass\PassInfo;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;

class OneToOneDto extends CourseDto
{
    public PassInfo $passes;

    public TimeRange $timeRange;
}
