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


class DNI extends Constraint
{

    public $requiredMessage = 'Se necesita un DNI';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \PlanB\DDD\Domain\VO\DNI;
    }

    public function sanitize($value)
    {
        $value = strtoupper((string)$value);
        return preg_replace('/(\s)/', '', $value);
    }
}
