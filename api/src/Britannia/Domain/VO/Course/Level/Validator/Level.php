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

namespace Britannia\Domain\VO\Course\Level\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;

class Level extends Constraint
{

    public $requiredMessage = 'Se necesita un nivel';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \Britannia\Domain\VO\Course\Level\Level;
    }
}
