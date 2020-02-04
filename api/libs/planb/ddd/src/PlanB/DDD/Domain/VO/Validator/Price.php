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


class Price extends Constraint
{
    public $requiredMessage = 'Se necesita un número positivo (ej. 99.99)';
    public $message = 'Se necesita un número positivo (ej. 99.99)';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \PlanB\DDD\Domain\VO\Price;
    }

    public function isEmpty($value): bool
    {
        return is_null($value);
    }


    public function sanitize($value)
    {
        if (is_numeric($value)) {
            return round($value * 1, 2);
        }

        return $value;
    }


}
