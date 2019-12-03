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

namespace Britannia\Domain\VO\Course\TimeTable\Validator;


use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class TimeSheet extends Constraint
{

    public $requiredMessage = 'Se necesita al menos una clase semanal';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \Britannia\Domain\VO\Course\TimeTable\TimeSheet;
    }

    public function sanitize($value)
    {
        $value['start'] = CarbonImmutable::make($value['start']);
        $value['end'] = CarbonImmutable::make($value['end']);

        return $value;
    }


}
