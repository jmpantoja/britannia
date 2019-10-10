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


use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class FullNameValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public const FULLNAME_REGEX_PATTERN = '/^[a-zA-z\- \.]*$/';

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return FullName::class;
    }

    public function handle($value, Constraint $constraint)
    {
        $this->validateField('firstName', $value['firstName'], [
            new NotBlank([
                'message' => $constraint->firstNameRequiredMessage
            ]),
            new Length([
                'min' => 3,
                'minMessage' => $constraint->lengthMessage
            ]),
            new Regex([
                'pattern' => self::FULLNAME_REGEX_PATTERN,
                'message' => $constraint->regexMessage
            ])
        ]);


        $this->validateField('lastName', $value['lastName'], [
            new NotBlank([
                'message' => $constraint->lastNameRequiredMessage
            ]),
            new Length([
                'min' => 6,
                'minMessage' => $constraint->lengthMessage
            ]),
            new Regex([
                'pattern' => self::FULLNAME_REGEX_PATTERN,
                'message' => $constraint->regexMessage
            ])
        ]);
    }
}
