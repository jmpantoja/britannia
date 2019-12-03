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


class Percent extends Constraint
{
    public $requiredMessage = 'Se necesita un número entero entre 0 y 100';
    public $message = 'Se necesita un número entero entre 0 y 100';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \PlanB\DDD\Domain\VO\Percent;
    }

    public function isEmpty($value): bool
    {
        return is_null($value);
    }

    public function sanitize($value)
    {
        if($this->isEmpty($value)){
            return 0;
        }
        return $value;
    }


}
