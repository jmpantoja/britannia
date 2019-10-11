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

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CityAddressValidator extends ConstraintValidator
{

    public const REGEX_PATTERN = '/^[\p{L}\- ]*$/u';

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return CityAddress::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof \PlanB\DDD\Domain\VO\CityAddress) {
            return;
        }

        $this->validateField('city', $value['city'], [
            new NotBlank([
                'message' => $constraint->cityRequiredMessage
            ]),
            new Regex([
                'pattern' => self::REGEX_PATTERN,
                'message' => $constraint->regexMessage
            ]),
            new Length([
                'min' => 4,
                'minMessage' => $constraint->lengthMessage
            ])
        ]);

        $this->validateField('province', $value['province'], [
            new NotBlank([
                'message' => $constraint->provinceRequiredMessage
            ]),
            new Regex([
                'pattern' => self::REGEX_PATTERN,
                'message' => $constraint->regexMessage
            ]),
            new Length([
                'min' => 4,
                'minMessage' => $constraint->lengthMessage
            ])
        ]);
    }
}
