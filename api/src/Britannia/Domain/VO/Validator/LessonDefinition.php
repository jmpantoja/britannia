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

namespace Britannia\Domain\VO\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;

class LessonDefinition extends Constraint
{

    public $requiredMessage = 'La clase no es correcta';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \Britannia\Domain\VO\LessonDefinition;
    }

}
