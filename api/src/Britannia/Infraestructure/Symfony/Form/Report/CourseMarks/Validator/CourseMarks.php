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

namespace Britannia\Infraestructure\Symfony\Form\Report\CourseMarks\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;

final class CourseMarks extends Constraint
{

    public $requiredMessage = 'Se necesita un valor';

    public function isValidType($value): bool
    {
        return is_array($value);
    }
}
