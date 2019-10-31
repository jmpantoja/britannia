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


class PositiveInteger extends Constraint
{
    public $requiredMessage = 'Se necesita un entero positivo (ej. 999)';
    public $message = 'Se necesita un entero positivo (ej. 999)';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \PlanB\DDD\Domain\VO\PositiveInteger;
    }

    public function sanitize($value)
    {
        if(is_numeric($value)){
            return $value *1;
        }

        return $value;
    }


}
