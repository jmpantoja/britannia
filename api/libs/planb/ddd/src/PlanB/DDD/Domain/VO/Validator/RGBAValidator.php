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


use PlanB\DDD\Domain\VO;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;


class RGBAValidator extends ConstraintValidator
{
    public function getConstraintType(): string
    {
        return RGBA::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\RGBA) {
            return;
        }

        $this->validateField('red', $value['red'], [
            new Range([
                'min' => 0,
                'max' => 255,
            ])
        ]);

        $this->validateField('green', $value['green'], [
            new Range([
                'min' => 0,
                'max' => 255,
            ])
        ]);

        $this->validateField('blue', $value['blue'], [
            new Range([
                'min' => 0,
                'max' => 255,
            ])
        ]);


        $this->validateField('alpha', $value['alpha'], [
            new Range([
                'min' => 0,
                'max' => 1,
            ])
        ]);

    }
}
