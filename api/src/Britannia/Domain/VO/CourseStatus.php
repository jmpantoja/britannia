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

namespace Britannia\Domain\VO;


use MabeEnum\Enum;

class CourseStatus extends Enum
{
    public const PENDING = 'Pendiente';
    public const ACTIVE = 'En Curso';
    public const FINALIZED = 'Finalizado';

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
