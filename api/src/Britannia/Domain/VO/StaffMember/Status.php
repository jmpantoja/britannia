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

namespace Britannia\Domain\VO\StaffMember;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self PERMANENT()
 * @method static self NON_PERMANENT()
 * @method static self CANDIDATE()
 * @method static self REJECTED()
 */
final class Status extends Enum
{
    private const PERMANENT = 'Fijo';
    private const NON_PERMANENT = 'Discontinuo';
    private const CANDIDATE = 'Candidato';
    private const REJECTED = 'Rechazado';

    public function isPermanent(): bool
    {
        return $this->is(static::PERMANENT());
    }

    public function isNonPermanent(): bool
    {
        return $this->is(static::NON_PERMANENT());
    }

    public function isCandidate(): bool
    {
        return $this->is(static::CANDIDATE());
    }

    public function isRejected(): bool
    {
        return $this->is(static::REJECTED());
    }
}
