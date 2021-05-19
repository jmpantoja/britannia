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

namespace Britannia\Domain\VO\Course;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self PENDING()
 * @method static self ACTIVE()
 * @method static self FINALIZED()
 */
class CourseStatus extends Enum
{

    private const PENDING = 'Pendiente';
    private const ACTIVE = 'En Curso';
    private const FINALIZED = 'Finalizado';

    public function isPending(): bool
    {
        return $this->is(CourseStatus::PENDING());
    }

    public function isActive(): bool
    {
        return $this->is(CourseStatus::ACTIVE());
    }

    public function isFinalized(): bool
    {
        return $this->is(CourseStatus::FINALIZED());
    }

    public function isOneOf(CourseStatus ...$allowedStatus)
    {
        return in_array($this, $allowedStatus);
    }

}
