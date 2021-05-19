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

namespace Britannia\Domain\VO\Student\Tutor\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;

class ChoicedTutor extends Constraint
{
    public $requiredMessage = 'Se necesita una alerta';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \Britannia\Domain\VO\Student\Tutor\ChoicedTutor;
    }
}
