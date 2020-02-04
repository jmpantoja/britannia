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

use Symfony\Component\Validator\Constraint as Base;

abstract class Constraint extends Base
{
    public $required = true;
    public $requiredMessage = 'El valor es requerido';
    public $message = 'Valor incorrecto';


    abstract public function isValidType($value): bool;


    public function isEmpty($value): bool
    {
        $filtered = array_filter((array)$value);
        return count($filtered) === 0;
    }

    public function isEmptyAndOptional($value): bool
    {
        return !$this->required && $this->isEmpty($value);
    }

    public function isEmptyAndRequired($value): bool
    {
        return $this->required && $this->isEmpty($value);
    }

    public function sanitize($value)
    {
        return $value;
    }

    public function normalize($value)
    {
        return $value;
    }
}
