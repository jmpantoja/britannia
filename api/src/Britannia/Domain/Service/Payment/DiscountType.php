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

namespace Britannia\Domain\Service\Payment;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self NONE()
 * @method static self FAMILY()
 * @method static self JOB_STATUS()
 */
class DiscountType extends Enum
{
    private const NONE = 'none';
    private const FAMILY = 'familiar';
    private const JOB_STATUS = 'laboral';

    public function isNormal(): bool
    {
        return $this->is(static::NONE());
    }

    public function isFamily(): bool
    {
        return $this->is(static::FAMILY());
    }

    public function isJobStatus(): bool
    {
        return $this->is(static::JOB_STATUS());
    }


}
