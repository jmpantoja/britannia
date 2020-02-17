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


use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\VO\Course\Assessment\Assessment;

interface AssessmentDtoInterface
{
    /**
     * @return Assessment
     */
    public function assessment(): Assessment;

    /**
     * @return AssessmentGenerator
     */
    public function assessmentGenerator(): AssessmentGenerator;
}
