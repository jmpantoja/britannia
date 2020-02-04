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


class PhoneNumber extends Constraint
{

    public $message = 'Número de teléfono incorrecto (ej. 555 12 34 56)';
    public $requiredMessage = 'Se necesita un número de teléfono';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \PlanB\DDD\Domain\VO\PhoneNumber;
    }

    public function sanitize($value)
    {
        if (empty($value)) {
            return $value;
        }

        $pattern = '/[\s \-]+/';
        $value['phoneNumber'] = preg_replace($pattern, '', $value['phoneNumber']);

        return $value;
    }

    public function normalize($value)
    {
        $matches = [];
        if (!preg_match('/^(\d{3})(\d{2})(\d{2})(\d{2})$/', $value['phoneNumber'], $matches)) {
            return $value;
        }

        unset($matches[0]);
        $value['phoneNumber'] = sprintf('%s %s %s %s', ...$matches);

        return $value;
    }

}
