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

namespace PlanB\DDD\Domain\VO\Validator;


class RGBA extends Constraint
{

    public $requiredMessage = 'Se necesita un color RGBA';
    public $message = 'El color no es válido RGBA';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \PlanB\DDD\Domain\VO\RGBA;
    }
}
