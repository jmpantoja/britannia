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
use Symfony\Component\Validator\Constraints\Type;

class PostalAddressValidator extends ConstraintValidator
{
    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return PostalAddress::class;
    }

    public function handle($value, Constraint $constraint)
    {

        $this->validateField('address', $value['address'], [
            new NotBlank([
                'message' => $constraint->addressRequiredMessage
            ]),
            new Length([
                'min' => 6,
                'minMessage' => $constraint->addressLenghtMessage
            ])
        ]);

        $this->validateField('postalCode', $value['postalCode'], [
            new NotBlank([
                'message' => $constraint->postalCodeRequiredMessage
            ]),

            new Type(\PlanB\DDD\Domain\VO\PostalCode::class)
        ]);
    }
}
