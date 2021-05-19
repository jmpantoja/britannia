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

namespace Britannia\Domain\VO\Course\Intensive;

use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self INTENSIVE()
 * @method static self NOT_INTENSIVE()
 */
class Intensive extends Enum
{
    private const INTENSIVE = 'Intensivo';
    private const NOT_INTENSIVE = 'No Intensivo';

    public function isIntensive(): bool
    {
        return $this->is(static::INTENSIVE());
    }
}
