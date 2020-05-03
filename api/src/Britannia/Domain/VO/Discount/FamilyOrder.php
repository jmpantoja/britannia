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

namespace Britannia\Domain\VO\Discount;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self UPPER()
 * @method static self LOWER()
 * @method static self DEFAULT()
 */
class FamilyOrder extends Enum
{
    private const UPPER = 'Primer hermano (curso de mayor importe)';
    private const LOWER = 'Segundo hermano (curso de menor importe)';
    private const DEFAULT = 'Tercer hermano y siguientes';

    public function isUpper(): bool
    {
        return $this->is(FamilyOrder::UPPER());
    }

    public function isLower(): bool
    {
        return $this->is(FamilyOrder::LOWER);
    }

    public function isDefault(): bool
    {
        return $this->is(FamilyOrder::DEFAULT);
    }
}
