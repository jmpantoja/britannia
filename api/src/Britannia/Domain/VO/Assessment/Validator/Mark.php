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

namespace Britannia\Domain\VO\Assessment\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;

class Mark extends Constraint
{

    public $requiredMessage = 'Se necesita una número entre 0 y 10';
    public $message = 'Se necesita una número entre 0 y 10';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \Britannia\Domain\VO\Assessment\Mark;
    }

    public function isEmpty($value): bool
    {

        if (is_numeric($value) and 0.0 === (float)$value) {
            return false;
        }

        return parent::isEmpty($value);
    }
}
