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

namespace Britannia\Domain\VO\Discount\Validator;

use PlanB\DDD\Domain\VO\Validator\Constraint;

class FamilyOrder extends Constraint
{

    public function isValidType($value): bool
    {
        return is_string($value) || $value instanceof \Britannia\Domain\VO\Discount\FamilyOrder;
    }
}
