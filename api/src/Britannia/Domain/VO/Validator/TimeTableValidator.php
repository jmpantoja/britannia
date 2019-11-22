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


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Britannia\Domain\VO;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class TimeTableValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return TimeTable::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\TimeTable) {
            return;
        }

        $this->validateField('start', $value['start'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

        $this->validateField('end', $value['end'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);
    }

}
