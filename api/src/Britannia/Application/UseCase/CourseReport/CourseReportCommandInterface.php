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

namespace Britannia\Application\UseCase\CourseReport;


use Britannia\Domain\Entity\Course\Course;

interface CourseReportCommandInterface
{
    /**
     * @return Course
     */
    public function course(): Course;
}
