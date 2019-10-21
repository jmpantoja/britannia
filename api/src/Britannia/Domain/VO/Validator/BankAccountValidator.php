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


use PlanB\DDD\Domain\VO\Validator\CityAddress;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use PlanB\DDD\Domain\VO\Validator\Iban;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class BankAccountValidator extends ConstraintValidator
{
    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return BankAccount::class;
    }

    public function handle($value, Constraint $constraint)
    {

        $this->validateField('titular', $value['titular'], [
            new NotBlank([
                'message' => 'Se necesita un titular'
            ])
        ]);

        $this->validateField('iban', $value['iban'], [
            new Iban()
        ]);

        $this->validateField('number', $value['number'], [
            new NotBlank([
                'message' => 'Se necesita un número de domiciliado'
            ]),
            new Type([
                'type' => 'integer',

            ]),
            new Positive([
                'message' => 'El número de domiciliado debe ser positivo'
            ])
        ]);

        $this->validateField('cityAddress', $value['cityAddress'], [
            new CityAddress()
        ]);
    }
}
