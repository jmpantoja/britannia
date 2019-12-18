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

namespace Britannia\Domain\VO\Mark\Validator;


use Britannia\Domain\VO\Mark\TermDefinition;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class Term extends Constraint
{

    public $requiredMessage = 'Se necesita un trimestre';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof TermDefinition;
    }
}
