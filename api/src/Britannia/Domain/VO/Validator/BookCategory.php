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

class BookCategory extends Constraint
{

    public $requiredMessage = 'Se necesita un tipo de libro';

    public function isValidType($value): bool
    {
        return is_scalar($value) || $value instanceof \Britannia\Domain\VO\BookCategory;
    }
}
