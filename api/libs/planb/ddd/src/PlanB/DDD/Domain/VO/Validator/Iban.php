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


class Iban extends Constraint
{
    public $requiredMessage = 'Se necesita un IBAN';


    public $message = 'IBAN incorrecto (ej. ES00 9999 9999 9999 9999 9999)';

    public $controlDigitIBANMessage = 'Digito de Control IBAN incorrecto';

    public $controlDigitCCCMessage = 'Digito de Control CCC incorrecto';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \PlanB\DDD\Domain\VO\Iban;
    }

    public function sanitize($value)
    {

        if (!is_string($value)) {
            return $value;
        }

        $value = strtoupper($value);
        $value = str_replace('-', '', $value);
        return preg_replace('/(\s)/', '', $value);
    }
}
