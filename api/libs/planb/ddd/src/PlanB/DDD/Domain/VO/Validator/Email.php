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


class Email extends Constraint
{
    public $requiredMessage = 'Se necesita un email';
    public $message = 'Email incorrecto (ej. usuario@example.com)';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \PlanB\DDD\Domain\VO\Email;
    }

    public function sanitize($value)
    {
        if ($value instanceof \PlanB\DDD\Domain\VO\Email) {
            return $value;
        }
        return trim((string)$value);
    }


}
