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


use MabeEnum\Enum;

class DiscountType extends Enum
{
    public const NONE = 'none';
    public const FAMILY = 'familiar';
    public const JOB_STATUS = 'laboral';

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
