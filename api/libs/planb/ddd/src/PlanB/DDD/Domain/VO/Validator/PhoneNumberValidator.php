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


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PhoneNumberValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return PhoneNumber::class;
    }

    public function handle($value, Constraint $constraint)
    {

        $this->validateField('phoneNumber', $value['phoneNumber'], [
            new NotBlank([
                'message' => $constraint->requiredMessage
            ]),
            new Regex([
                'message' => $constraint->message,
                'pattern' => '/^\d{9}$/'
            ])
        ]);

//        $this->validateValue($value, [
//            new Regex([
//                'message' => $constraint->message,
//                'pattern' => '/^\d{9}$/'
//            ])
//        ]);

    }
}
