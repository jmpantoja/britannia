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

namespace Britannia\Domain\VO\Course\Pass\Validator;


use Britannia\Domain\Entity\Lesson\Lesson;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class Pass extends Constraint
{

    public $requiredMessage = 'Se necesita un valor';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \Britannia\Domain\Entity\Course\Pass\Pass;
    }

    public function sanitize($value)
    {
        $value['start'] = CarbonImmutable::make($value['start']);
        return $value;
    }
}
