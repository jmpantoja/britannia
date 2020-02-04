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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class PostalCodeValidator extends ConstraintValidator
{
    public const POSTAL_CODE_REGEX = '/^\d{5}$/';

    public function getConstraintType(): string
    {
        return PostalCode::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\PostalCode) {
            return;
        }

        $this->validateValue($value, [
            new NotBlank(),
            new Regex([
                'pattern' => self::POSTAL_CODE_REGEX,
                'message' => $constraint->message
            ])
        ]);

    }
}
