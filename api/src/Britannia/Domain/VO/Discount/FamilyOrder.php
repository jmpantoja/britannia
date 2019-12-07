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


use MabeEnum\Enum;
use PlanB\DDD\Domain\VO\Percent;

class FamilyOrder extends Enum
{
    public const UPPER = 'Hermano con el curso de mayor precio';
    public const LOWER = 'Hermano con el curso de menor precio';
    public const DEFAULT = 'Resto de hermanos';

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
