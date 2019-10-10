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
use Symfony\Component\Validator\Constraints;


class EmailValidator extends ConstraintValidator
{
    public const POSTAL_CODE_REGEX = '/^\d{5}$/';

    public function getConstraintType(): string
    {
        return Email::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Email) {
            return;
        }

        $this->validateValue($value, [
            new Constraints\Email([
                'mode' => Constraints\Email::VALIDATION_MODE_HTML5,
                'message' => $constraint->message
            ])
        ]);

    }
}
